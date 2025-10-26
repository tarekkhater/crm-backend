<?php

namespace App\Http\Controllers\admin\Users\Customer;

use App\Models\Permission;
use App\Exports\UsersClientExport;
use App\Exports\UsersLeadExport;
use App\Http\Controllers\Controller;
use App\Models\AgentUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserManager;
use App\Models\IBClient;
use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Identity;
use App\Models\Message;
use App\Models\Trade;
use App\Models\AgentNotes;
use App\Models\Admin;
use App\Http\Resources\CRM\APi\User\UserResource;
use App\Models\UserCertificate;
use Carbon\Carbon;
use App\Models\percentageCertificates;
use App\Models\Document;
use Illuminate\Support\Facades\Mail;
use  App\Mail\CannotTrade;
use App\Mail\Enablewithdraw;
use App\Models\InfoTradeUser;
use App\Models\CurrencyPair;
use App\Services\TradeService;
use App\Http\Resources\PositionResource;

class IndexController extends Controller
{
    protected $seviceTrade;
    public function __construct()
    {
        $this->seviceTrade = new TradeService();
        // $this->middleware('RoleMiddleware:view-settings_update-settings')->only('overnights');
        // $this->middleware('RoleMiddleware:verify-all-emails')->only('verifyAccounts');
        // $this->middleware('RoleMiddleware:view-transaction')->only('transactions');
        // $this->middleware('RoleMiddleware:delete-transaction')->only('DelTrans');

    }



    public function index(Request $request)
    {
        // الحصول على معرفات المستخدمين بطريقة مباشرة
        $ids = User::pluck('id')->toArray(); // هذا سيعيد جميع معرفات المستخدمين
        $users = User::whereIn('id', $ids)->where('type_id', 2)->paginate(15);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function ConvertUSers(Request $request)
    {
        // Validate the request
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:users,id', // Ensure each ID exists
            'type' => 'required|numeric', // Ensure type is numeric
        ]);

        // Keep track of updated users
        $updatedUsers = [];

        foreach ($request->ids as $value) {
            $user = User::find($value);
            if ($user) { // Check if the user exists
                $user->type_id = $request->type;
                $user->save();
                $updatedUsers[] = $user; // Optionally track which users were updated
            } else {
                // Log the missing user ID or handle the case as needed
                \Log::warning("User with ID $value not found.");
            }
        }

        // Optionally return the updated users or a count
        $this->setMessage("success");
        return $this->sendApiResonse(['updated_users' => $updatedUsers]); // Include updated users if needed
    }


    public function show($id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {
            $user->load(['UserInfo', 'identity', 'Payments', 'myWithdrawals', 'deposits', 'transactions', 'wireAccounts', 'trades', 'messages', 'Manager.manager']);
            $this->setData(new UserResource($user));
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function ActiveCustomer(Request $request)
    {
        $user = User::where('id', $request->id)->update([
            'email_verified_at' => date('Y-m-d'),
        ]);
        $user = User::where('id', $request->id)->first();
        $user->load(['UserInfo', 'Payments', 'myWithdrawals', 'deposits', 'transactions', 'wireAccounts', 'trades', 'messages']);
        $this->setData(new UserResource($user));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function DisabledTrade(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->update([
            'allow_trade' => $user->allow_trade == '0' ? '1' : '0',
        ]);
        $user = User::where('id', $request->id)->first();
        $user->load(['UserInfo', 'Payments', 'myWithdrawals', 'deposits', 'transactions', 'wireAccounts', 'trades', 'messages']);
        if ($user->allow_trade == '0') {
            Mail::to("$user->email")->send(new CannotTrade($user, 'suspended from your account', 'تم تعليق حسابك من التداول علي المنصه. يرجى الاتصال بالدعم للحصول على المزيد من التفاصيل.'));
        } else {
            Mail::to("$user->email")->send(new CannotTrade($user, 'active trading for your account', 'تم تفعيل حسابك للتداول على المنصة. يرجى  الاتصال بالدعم في حاله مواجهه اي مشكله.'));
        }

        $this->setData(new UserResource($user));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function DisabledTradeHourse(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->update([
            'allow_trade_after_hours' => $user->allow_trade_after_hours == '0' ? '1' : '0',
        ]);
        $user = User::where('id', $request->id)->first();
        $user->load(['UserInfo', 'Payments', 'myWithdrawals', 'deposits', 'transactions', 'wireAccounts', 'trades', 'messages']);
        if ($user->allow_trade_after_hours == '1') {
            Mail::to("$user->email")->send(new CannotTrade($user, 'active trading for your account', 'تم تفعيل خدمه  التداول على المنصة في حاله اذا كان السوق مقفول . يرجى  الاتصال بالدعم في حاله مواجهه اي مشكله.'));
        } else {

            Mail::to("$user->email")->send(new CannotTrade($user, 'active trading for your account', 'تم تعطيل خدمه  التداول على المنصة في حاله اذا كان السوق مقفول . يرجى  الاتصال بالدعم في حاله مواجهه اي مشكله.'));
        }
        $this->setData(new UserResource($user));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function DisabledWithdrawal(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->update([
            'can_withdraw' => $user->can_withdraw == '0' ? '1' : '0',
        ]);
        $user = User::where('id', $request->id)->first();
        $user->load(['UserInfo', 'Payments', 'myWithdrawals', 'deposits', 'transactions', 'wireAccounts', 'trades', 'messages']);
        if ($user->can_withdraw == '1') {
            Mail::to("$user->email")->send(new Enablewithdraw(
                $user,
                'فتح السحب لحسابك',
                'تم تفعيل خدمة السحب لحسابك بنجاح. يمكنك الآن سحب الأموال من حسابك. في حال واجهت أي مشكلة، يرجى الاتصال بالدعم.'
            ));
        } else {

            Mail::to("$user->email")->send(new Enablewithdraw(
                $user,
                'إغلاق السحب لحسابك',
                'تم تعطيل خدمة السحب لحسابك، يرجى الاتصال بالدعم للحصول على المساعدة.'
            ));
        }
        $this->setData(new UserResource($user));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function Deposit($id)
    {
        $result = Deposit::where('user_id', $id)->with(['user', 'user.TradingAccount', 'plan', 'account'])->orderByDesc('id')->paginate(15);
        $this->setData($result);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function ExportLeads(Request $request)
    {
        try {
            $request->validate(['type' => 'required|in:1,2']); // Validate 'type' to be either 1 or 2

            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = $request->type == 1 ? "Clients_{$timestamp}.xls" : "Leads_{$timestamp}.xls";
            $filePath = "upload/excel/export/{$fileName}";

            // Instantiate export class
            $exportClass = new UsersLeadExport($request);

            // Store the Excel file
            $stored = Excel::store($exportClass, $filePath, 'public');

            if (!$stored || !\Storage::disk('public')->exists($filePath)) {
                throw new \Exception("Failed to create export file at path: {$filePath}");
            }

            return response()->json([
                'message' => "Export successful",
                'file_path' => $filePath
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Invalid export type specified',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error exporting leads: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error exporting leads',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function Withdrawals($id)
    {
        $result = Withdrawal::where('user_id', $id)->with(['user', 'user.TradingAccount', 'plan', 'account'])->paginate(15);
        $this->setData($result);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function documents($id)
    {
        $result = Identity::where('user_id', $id)->paginate(15);
        $this->setData($result);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }






    public function openTrade($id)
    {
        $trades = $this->seviceTrade->index([$id]);
        return PositionResource::collection($trades);
        $this->setMessage("success");
        return $this->sendApiResonse();

        $trades = Trade::where('is_pending_order', null)->whereStatus(0)->where('user_id', $id)->paginate(15);
        $result = [];
        foreach ($trades as $data) {
            if ($data->user != null) {
                $coinprice = $this->getCurRate($data->currency->sym, $data->currency->base, $data->currency->type);
                if ($coinprice > 0) {
                    $asset = CurrencyPair::find($data->currency->id);
                    $asset->rate = $coinprice;
                    $asset->save();
                }
                // $this->updateTradeProfit($data->id,$coinprice,$this->truncate_number($data->opening_price,5),$data->leverage);
                $result[] = [
                    "id" => $data->id,
                    "user_id" => $data->user->id,
                    "name" => $data->user->name,
                    "email" => $data->user->email,
                    "trade_type" => $data->trade_type,
                    "currency_pair" => $data->currency_pair,
                    "duration" => $data->duration,
                    "traded_amount" => $this->truncate_number($data->amount, 5) / ($data->lavarag > 0 ? $data->lavarag : 1),
                    "close_at" => date('Y M d', strtotime($data->close_at)),
                    "com" => $data->com,
                    "leverage" => $data->leverage,
                    "opening_price" => $this->truncate_number($data->opening_price, 5),
                    "closing_price" => $data->closing_price,
                    "result" => $data->result,
                    "status" => $data->status,
                    "open_at" => date('Y M d', strtotime($data->created_at)),
                    "pnl" => $this->truncate_number($data->profit, 5),
                    "current_price" => $this->truncate_number($coinprice > 0 ? $coinprice : $data->currency->rate, 5),
                    "currency" => $data->currency,
                    "asset" => $data->currency,
                    "amount" => $data->amount,
                    "qty" => $data->leverage,
                    "total" => $data->rate,
                    "take_profit" => $data->take_profit,
                    "stop_loss" => $data->stop_loss,
                    "is_pending_order" => $data->is_pending_order == 0 || $data->is_pending_order == null ? false : true,
                ];
            }
        }
        $data = [
            'data'         => $result,
            'current_page' => $trades->currentPage(),
            'from'         => $trades->firstItem(),
            'last_page'    => $trades->lastPage(),
            'links'        => [],
            'per_page'     => $trades->perPage(),
            'to'           => $trades->lastItem(),
            'total'        => $trades->total(),
        ];
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function pendingTrade($id)
    {
        $result = Trade::where('is_pending_order', '<>', null)->whereStatus(0)->where('user_id', $id)->paginate(15);
        $data = [
            'data'         => $this->resposeData($result->items()),
            'current_page' => $result->currentPage(),
            'from'         => $result->firstItem(),
            'last_page'    => $result->lastPage(),
            'links'        => [],
            'per_page'     => $result->perPage(),
            'to'           => $result->lastItem(),
            'total'        => $result->total(),
        ];
        $this->setData($this->resposeData($result->items()));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function CloseTrade($id)
    {
        $trades = $this->seviceTrade->indexClose([$id]);
        return PositionResource::collection($trades);
        $result = Trade::whereStatus(1)->where('user_id', $id)->paginate(15);
        $data = [
            'data'         => $this->resposeDataclose($result->items()),
            'current_page' => $result->currentPage(),
            'from'         => $result->firstItem(),
            'last_page'    => $result->lastPage(),
            'links'        => [],
            'per_page'     => $result->perPage(),
            'to'           => $result->lastItem(),
            'total'        => $result->total(),
        ];
        $this->setData($this->resposeDataclose($result->items()));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }







    public function transactionwallet($id)
    {
        $wallets = Wallet::where('user_id', $id)->with(['user'])->paginate(15);

        // $data = [];
        $data = [
            'data'         => [],
            'current_page' => $wallets->currentPage(),
            'from'         => $wallets->firstItem(),
            'last_page'    => $wallets->lastPage(),
            'links'        => [],
            'per_page'     => $wallets->perPage(),
            'to'           => $wallets->lastItem(),
            'total'        => $wallets->total(),
        ];

        foreach ($wallets->items() as $value) {
            $data['data'][] = [
                'id' => $value->id,
                "from" => $value->from == "1" ? "Awaiting Deposit" : "Trading Wallet",
                "to" => $value->to == "1" ? "Awaiting Deposit" : "Trading Wallet",
                "amount" => $value->amount,
                "cur" => $this->user->userInfo->cur ?? "eg",
                'date' => date('Y M d', strtotime($value->created_at)),
                "status" => $this->statusWallet($value->status),

            ];
        }
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function statusWallet($status)
    {
        switch ($status) {
            case null:
                return "Pending";
                break;
            case 0:
                return "Failed";
                break;
            default:
                return "Success";
        }
    }



    public function notes(Request $request, $id)
    {
        $notes = AgentNotes::where('user_id', $id)->with(['user', 'agent'])->orderByDesc('id')->paginate(15);
        if (isset($request->note)) {
            $notes = AgentNotes::where('user_id', $id)->with(['user', 'agent'])->where('content', 'LIKE', '%' . $request->note . '%')->orderByDesc('id')->paginate(15);
        }
        $result = [
            'data'         => [],
            'current_page' => $notes->currentPage(),
            'from'         => $notes->firstItem(),
            'last_page'    => $notes->lastPage(),
            'links'        => [],
            'per_page'     => $notes->perPage(),
            'to'           => $notes->lastItem(),
            'total'        => $notes->total(),
        ];

        foreach ($notes->items() as $note) {
            $result['data'][] = [
                'content' => $note->content,
                'message_by' => $note->message_by == 0 ? 'agent' : 'teamleader',
                'agent' => Admin::find($note->agent_id)->email ?? "user@quantumprime.app",
                'user' => $note->user,
                'created_at' => Carbon::parse($note->created_at)->format('d M Y, H:i')
            ];
        }
        $this->setData($result);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function mailing(Request $request, $id)
    {
        $messgaes = Message::where('user_id', $id)->paginate(15);
        if (isset($request->note)) {
            $messgaes = Message::where('user_id', $id)->where('subject', 'LIKE', '%' . $request->note . '%')->orWhere('message', 'LIKE', '%' . $request->note . '%')->paginate(15);
        }
        $messgaes->load('user');
        $this->setMessage("success");
        $this->setData($messgaes);
        return $this->sendApiResonse();
    }

    public function kyc($id)
    {

        $idantity = Identity::where('user_id', $id)->with(['user', 'modified'])->first();
        $result = [];
        if ($idantity && $idantity->documents != null) {
            foreach ($idantity->documents as $index => $value) {
                if ($value->title != "Back Credit Card" && $value->title != "Front Credit Card") {
                    $result[] = [
                        'id' => $value->id,
                        'file' => asset($value->value),
                        'title' => $value->title,
                        'note' => $value->note,
                        'status' => $value->status,
                    ];
                }
            }
        } else {
            $result = [
                [
                    'id' => 0,
                    'file' => asset('notfound.jpg'),
                    'title' => "Front Id",
                    'note' => "note",
                    'status' => 0,
                ],
                [
                    'id' => 0,
                    'file' => asset('notfound.jpg'),
                    'title' => "Back Id",
                    'note' => "note",
                    'status' => 0,
                ],

                [
                    'id' => 0,
                    'file' => asset('notfound.jpg'),
                    'title' => "Selfie",
                    'note' => "note",
                    'status' => 0,
                ],
                [
                    'id' => 0,
                    'file' => asset('notfound.jpg'),
                    'title' => "por",
                    'note' => "note",
                    'status' => 0,
                ],
            ];
        }



        $this->setMessage("success");
        $this->setData($result);
        return $this->sendApiResonse();
    }


    public function getCertificates($id)
    {
        $certificates =  UserCertificate::where('user_id', $id)->with(['user'])->get();
        $data = [];
        $profit = 0;
        $total = 0;
        $percentage = 0;
        $currentDate = Carbon::now();

        foreach ($certificates as $certificate) {
            $findcertificed = percentageCertificates::where('certificate_id', $certificate->id)->where('status', '1')->first();
            if ($findcertificed) {
                $profit = $findcertificed->profit;
                $total = $findcertificed->total;
                $percentage = $findcertificed->percentage;
            } else {
                $profit = $certificate->profit;
                $total = $certificate->total;
                $percentage = $certificate->percentage;
            }
            $data[] = [
                'id' => $certificate->id,
                'user' => [
                    'id' => $id,
                    'name' => $certificate->user->name . ' ' . $certificate->user->surname,
                    'email' => $certificate->user->email
                ],
                'name' => $certificate->certificate->name,
                'amount' => $certificate->certificate->amount . ' $',
                'duration' => $this->getDuration($certificate->duration),
                'period' => $this->getPeriod($certificate->period),
                'start' => $certificate->start_date,
                'expired' => $certificate->end_date,
                'percentage' => $percentage,
                'profit' => number_format(($profit / 23), 2, ",", ".") . ' $',
                'total' => number_format($total, 2, ",", ".") . ' $',
                'expacted_profit' => ($certificate->status == 1 ? ($certificate->duration * 12) * $certificate->profit : 0) . ' $',
                'status' => $certificate->status_text,
                'history' => $this->historyresponse($certificate->history, $certificate->duration)
            ];
            //   $data['profit'] +=$certificate->total;
            //  if($certificate->status == 1){
            //      $data['accepted'] += 1;
            //  }

            //   if($certificate->status == 3){
            //      $data['rejected'] += 1;
            //  }
        }

        // $data['profit'] = number_format($data['profit'],2,",",".");


        $this->setMessage("success");
        $this->setData($data);
        return $this->sendApiResonse();
    }

    public function historyresponse($histories, $duration)
    {
        $data = [];
        foreach ($histories as $history) {
            $data[] = [
                'percentage' => $history->percentage,
                'profit' => number_format(($history->profit / 23), 2, ",", "."),
                'total' => number_format($history->total, 2, ",", "."),
                'expacted_profit' => (($duration * 12) * $history->profit),
                'status' => $history->status ? 'proccess' : 'finished',
            ];
        }
        return $data;
    }



    public function destroy(Request $request)
    {
        // Validate the request to ensure ids are provided
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:users,id', // Ensure each ID exists
        ]);

        // Iterate through the provided IDs and attempt to delete each user
        foreach ($request->ids as $value) {
            $user = User::find($value);
            if ($user) { // Check if the user exists
                $user->delete(); // Delete the user
            } else {
                // Log or handle the case of a missing user
                \Log::warning("User with ID $value not found for deletion.");
            }
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function responseData($documents)
    {
        $data = [];
        foreach ($documents as $document) {
            if ($document->user != null) {
                $data[] = [
                    'id' => $document->id,
                    'user_id' => $document->user->id,
                    'name' => $document->user->name != null ? $document->user->name : "name",
                    'email' => $document->user->email,
                    'number' => $document->user->phone,
                    'front_id' => baseUrl() . $document->front_id,
                    'back_id' => baseUrl() . $document->back_id,
                    'front_credit_card' => baseUrl() . $document->front_credit_card,
                    'back_credit_card' => baseUrl() . $document->back_credit_card,
                    'selfie' => baseUrl() . $document->selfie,
                    'por' => baseUrl() . $document->por,
                    'status' => $document->status,
                    'proof' => $document->proof,
                ];
            }
        }
        return $data;
    }

    public function getDuration($value)
    {
        return $value . "year";
        // switch($value) {
        //     case '1':
        //         return "1 year";
        //         break;
        //     case '2':
        //         return "2 years";
        //         break;
        //     case '3':
        //     return "3 years";
        //             break;
        //         default:
        //         return "unknown";
        //         }
    }
    public function getPeriod($value)
    {
        switch ($value) {
            case '1':
                return "monthly";
                break;
            case '2':
                return "quarterly";
                break;
            case '3':
                return "Semi-annually";
                break;
            default:
                return "annually";
        }
    }


    public function getDaysCount($givenDate)
    {
        // Create a Carbon instance for the given date
        $targetDate = Carbon::createFromFormat('Y-m-d', $givenDate);
        // Get today's date using Carbon
        $today = Carbon::today();
        // Calculate the difference in days (positive for future date, negative for past date)
        $daysCount = $today->diffInDays($targetDate);

        // Return the result (you can return this in any format, here we return it as JSON)
        return  $daysCount + 1;
    }

    public function resposeData($datas)
    {
        $result = [];
        foreach ($datas as $data) {
            if ($data->user != null) {
                $coinprice = $this->getCurRate($data->currency->sym, $data->currency->base, $data->currency->type);
                $this->updateTradeProfit($data->id, $coinprice, $this->truncate_number($data->opening_price, 5), $data->leverage);
                $result[] = [
                    "id" => $data->id,
                    "user_id" => $data->user->id,
                    "name" => $data->user->name,
                    "email" => $data->user->email,
                    "trade_type" => $data->trade_type,
                    "currency_pair" => $data->currency_pair,
                    "duration" => $data->duration,
                    "traded_amount" => $this->truncate_number($data->amount, 5) / ($data->lavarag > 0 ? $data->lavarag : 1),
                    "close_at" => date('Y M d', strtotime($data->close_at)),
                    "com" => $data->com,
                    "leverage" => $data->leverage,
                    "opening_price" => $this->truncate_number($data->opening_price, 5),
                    "closing_price" => $data->closing_price,
                    "result" => $data->result,
                    "status" => $data->status,
                    "open_at" => date('Y M d', strtotime($data->created_at)),
                    "pnl" => $this->truncate_number($data->profit, 5),
                    "current_price" => $this->truncate_number($coinprice, 5),
                    "currency" => $data->currency,
                    "asset" => $data->currency,
                    "amount" => $data->amount,
                    "qty" => $data->leverage,
                    "total" => $data->rate,
                    "take_profit" => $data->take_profit,
                    "stop_loss" => $data->stop_loss,
                    "is_pending_order" => $data->is_pending_order == 0 || $data->is_pending_order == null ? false : true,
                ];
            }
        }
        return $result;
    }

    public function updateTradeProfit($id, $coinPrice, $opening_price, $qty)
    {
        $trade = Trade::findOrFail($id);
        $rate = optional($trade->currency)->rate;

        $coinPrice = $this->getCurRate($trade->currency->sym, $trade->currency->base, $trade->currency->type);
        if ($coinPrice === 0) {
            $coinPrice = $rate;
        }
        $old_coin_value = $trade->amount / $trade->opening_price;
        $trade->closing_price =  $coinPrice;
        $cryptoRate = $coinPrice;
        $trade->close_at = null;
        // $pl = abs(($old_coin_value * $cryptoRate) - $trade->amount);
        $pl = abs(($coinPrice  - $opening_price) * $qty);
        $trade->profit = $pl;

        if ($trade->trade_type == 'Buy') {
            if ($opening_price > $cryptoRate) {
                $trade->profit = $pl * (-1);
                $trade->save();
            } else if ($opening_price < $cryptoRate) {
                $trade->profit = $pl;
                $trade->save();
            }
        } else if ($trade->trade_type == 'Sell') {
            if ($opening_price > $cryptoRate) {
                $trade->profit = $pl * (-1);
                $trade->save();
            } else if ($opening_price > $cryptoRate) {
                $trade->profit = $pl;
                $trade->save();
            }
        }

        //check profit / loss
        if ($trade->is_take_profit) {
            if ($trade->profit >= $trade->take_profit) {
                $this->updateTrade($trade->id);
            }
        }
        if ($trade->is_stop_loss) {
            if (((-1) * $trade->stop_loss) >= $trade->profit) {
                $this->updateTrade($trade->id);
            }
        }
    }

    public function updateTrade($id)
    {
        //        $user = Auth::user();
        $trade = Trade::findOrFail($id);
        $user = User::findOrFail($trade->user_id);

        $trade->close_at = Carbon::now();

        $pl =  $trade->profit;

        if ($trade->status === 0) {
            if ($trade->trade_type == 'Buy') {
                if ($trade->opening_price > $trade->closing_price) {

                    $msg = 'Traded  ' . optional($trade->currency)->name . ' lost';
                    $amt = $trade->traded_amount + ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 2;
                    $trade->status = 1;
                    $trade->save();
                    return 2;
                } else if ($trade->opening_price < $trade->closing_price) {
                    //                    $user->balance += ($trade->traded_amount + $pl);
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' won';
                    $amt = $trade->traded_amount + ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);
                    $user->save();
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                } else {
                    //                    $user->balance += $trade->traded_amount;
                    //                    $user->save();
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' draw';
                    $amt = $trade->traded_amount;
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 3;
                    $trade->status = 1;
                    $trade->save();
                    return 3;
                }
            } else if ($trade->trade_type == 'Sell') {
                if ($trade->opening_price < $trade->closing_price) {
                    //                    $user->balance += ($trade->traded_amount - $pl);
                    //                    $user->save();

                    $msg = 'Traded  ' . optional($trade->currency)->name . ' lost';
                    $amt = $trade->traded_amount + ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 2;
                    $trade->status = 1;
                    $trade->save();
                    return 2;
                } else if ($trade->opening_price > $trade->closing_price) {
                    //                    $user->balance += ($trade->traded_amount + $pl);
                    //                    $user->save();
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' won';
                    $amt = $trade->traded_amount + ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                } else {
                    //                    $user->balance += $trade->traded_amount;
                    //                    $user->save();

                    $msg = 'Traded  ' . optional($trade->currency)->name . ' draw';
                    $amt = $trade->traded_amount;
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 3;
                    $trade->status = 1;
                    $trade->save();
                    return 3;
                }
            }
        }
    }

    public function resposeDataclose($datas)
    {
        $result = [];

        foreach ($datas as $data) {
            if ($data->user != null) {
                $result[] = [
                    "id" => $data->id,
                    "user_id" => $data->user->id,
                    "name" => $data->user->name,
                    "email" => $data->user->email,
                    "trade_type" => $data->trade_type,
                    "pnl" => $data->profit,
                    "currency_pair" => $data->currency_pair,
                    "duration" => $data->duration,
                    "traded_amount" => $data->traded_amount,
                    "close_at" => date('Y M d', strtotime($data->close_at)),
                    "com" => $data->com,
                    "opening_price" => $data->opening_price,
                    "closing_price" => $data->closing_price,
                    "traded_amount" => $data->traded_amount,
                    "result" => $data->result,
                    "status" => $data->status,
                    "open_at" => date('Y M d', strtotime($data->created_at)),
                    "current_price" => $this->getCurRate($data->currency->sym, $data->currency->base, $data->currency->type),
                    "currency" => $data->currency,
                    "asset" => $data->currency,
                    "amount" => $data->amount,
                    "qty" => $data->qty,
                ];
            }
        }
        return $result;
    }

    public function resposeDataall($datas)
    {
        $result = [];
        foreach ($datas as $data) {
            if ($data->user != null) {
                $result[] = [
                    "id" => $data->id,
                    "user_id" => $data->user->id,
                    "name" => $data->user->name,
                    "email" => $data->user->email,
                    "trade_type" => $data->trade_type,
                    "pnl" => $data->profit,
                    "currency_pair" => $data->currency_pair,
                    "duration" => $data->duration,
                    "traded_amount" => $data->traded_amount,
                    "close_at" => date('Y M d', strtotime($data->close_at)),
                    "com" => $data->com,
                    "opening_price" => $data->opening_price,
                    "closing_price" => $data->closing_price,
                    "traded_amount" => $data->traded_amount,
                    "result" => $this->getStatusResult($data->result),
                    "status" => $this->getStatus($data->status),
                    "open_at" => date('Y M d', strtotime($data->created_at)),
                    "current_price" => $this->getCurRate($data->currency->sym, $data->currency->base, $data->currency->type),
                    "currency" => $data->currency,
                    "asset" => $data->currency,
                    "amount" => $data->amount,
                    "qty" => $data->qty,
                ];
            }
        }
        return $result;
    }

    public function getStatus($status)
    {
        if ($status) {
            return "Complete";
        } else {
            return "Running";
        }
    }

    public function getStatusResult($status)
    {
        if ($status == 1) {
            return "win";
        } else if ($status == 2) {
            return "lose";
        } else {
            return "lose";
        }
    }

    public function balance(Request $request, $id)
    {

        $user = InfoTradeUser::where('user_id', $id)->first();
        // if($request->type == "awaiting"){
        //     if($request->balance < 0 ){

        //         $wallet = Wallet::where('id', $request->id)->first();
        //         $wallet->status = 1;
        //         $wallet->save();
        //         $user->awaiting_deposit=$request->balance;
        //         $user->balance += $request->balance;
        //     }else{
        //         Wallet::create([
        //             "user_id"=>$user->user_id,
        //             "from"=>"1",
        //             "to"=>"1",
        //             "amount"=> $request->balance,
        //             "cur"=>$user->cur,
        //             'date'=>now(),
        //             "status"=> NULL,    
        //         ]);
        //         $user->awaiting_deposit+=$request->balance;
        //         $user->balance += $request->balance;
        //     }

        // }else{
        //  $user->balance=$request->balance;  
        // }
        if($request->type == "awaiting"){
            if($user->awaiting_deposit > 0 ){
                if($request->balance > $user->awaiting_deposit  ){
                    $user->balance += ($request->balance - $user->awaiting_deposit);
                    $user->awaiting_deposit=$request->balance;
                }else{
                    $user->balance -= ($user->awaiting_deposit - $request->balance);
                    $user->awaiting_deposit=$request->balance;
                }

            }else{
                $user->awaiting_deposit=$request->balance;
                $user->balance += $request->balance;
            }

        }else{
            $user->balance = $request->balance ;
        }
       
        $user->save();
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    function truncate_number($number, $decimals = 2)
    {
        $factor = pow(10, $decimals);
        return floor($number * $factor) / $factor;
    }
}
