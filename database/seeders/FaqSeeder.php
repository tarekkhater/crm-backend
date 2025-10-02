<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = [
            ['title' => 'How to update my account details?','details' => 'To change any of the personal details in your trading account please contact us to explain the reason for this change and provide us
                                   with the relevant information, e.g. name change due to marriage, or change of residential address.
                                    We will then review and action your request in accordance with our regulatory requirements and obligations.
                             '],
            ['title' => 'How do I verify my payment method?','details' => 'Verify payment by uploading proof of payment. Once proof of payment is uploaded, your payment will be reviewed and cleared upon confirmation.'],
            ['title' => 'Is my payment processing fees covered?', 'details' => 'As part of our commitment to offer the best trading conditions available,
                                                we cover most payment processing fees.'],
            ['title' => 'Are my funds at risk in case of Insolvency/Bankruptcy?','details' => 'All clients funds are held in segregated client wallets to ensure maximum protection of the funds'],
            ['title' => ' Can I deposit from a joint bank account?', 'details' => 'Usually yes, however you may be required to provide supporting documentation to confirm that you are
                                            one of the named parties on the account.
                                            Nevertheless, in certain countries and under certain circumstances this may not be possible due to regulatory obligations.'],
            ['title' => 'Can I deposit using a corporate credit/debit card/account?','details' => 'No. All funds must originate from a payment method registered in your own name.'],
            ['title' => 'Do you add dividends?','details' => 'Dividends are the portion of corporate profits that are allocated to shareholders,
                                    and the cut-off date for share ownership in order to qualify for a dividend is known as ex-dividend date.
                                    You trade CFDs on equities, therefore, you do not actually own the share itself.
                                    If you have an equity or ETF CFD position open on the ex-dividend date, an adjustment will be made to your account in respect of the dividend paid on the underlying market. If you hold a buy position you will receive the dividend as a positive adjustment to your account. However,
                                    if you hold a sell position there will be a negative adjustment. Please note that voting rights are not acquired with equity CFDs.'],
            ['title' => 'Do you offer a free Rollover Service?', 'details' => 'Most of the instruments we offer that are based on a futures contracts, have a rollover date.
                                            You can find this information by clicking on the "Details" link on the main trading platform screen next to each instrument.
                                            Whenever a futures contract reaches its automatic rollover date as defined for the instrument, all open positions and orders are automatically rolled over to the next futures contract by bittraders, free of charge.
                                            In order to nullify the impact on the valuation of the open position, given the change in the underlying instrument’s rate (price) for the new contract period, a compensating adjustment is made to allow you to keep your positions open without affecting your Equity level. Stop Orders and Limit Orders are also adjusted proportionally to reflect the rate of the new contract. The value of your position continues to reflect the impact of market movement based on your original opening level.
                                            For more information about how rollover adjustments are calculated, please read: ”What is a Rollover Adjustment?"'],
            ['title' => 'How are prices calculated?', 'details' => 'We quotes prices with reference to the price of the relevant underlying financial instrument and its spread.'],
            ['title' => 'Can I have more than one trading account?', 'details' => 'We recommend clients focus on one trading account, and we reserve the right to close subsequent accounts.'],
            ['title' => 'Do I risk any real money while using the Demo account?', 'details' => 'No, using the demo account is completely risk-free as you can’t lose real money.'],
            ['title' => 'What are the benefits of trading with a regulated company?', 'details' => '     One of the benefits of trading with a regulated firm is that you know you are trading on a reliable and reputable platform in a regulated environment,
                                    which has stringent rules and regulations designed, in particular, to protect the interests of retail clients'],
            ['title' => 'How long does withdrawals take?', 'details' => 'Withdrawals are paid in a space of 0 - 24 hours, considering the functioning of the blockchain network when there are many transactions to be added to a block.'],
            ['title' => 'Is DEPOSIT Automatic?', 'details' => 'All deposits are automatically credited to your balance, as you send BTC to the unique wallet address generated during funding request.'],
            ['title' => 'Can I invest in multiple plans?', 'details' => 'Yes, you can invest in multiple plans. All investments run concurrently.'],
            ['title' => 'When can I withdraw money?', 'details' => 'You can make a request at anytime, including weekends.'],
            ['title' => ' How long does it take to credit my deposit?', 'details' => 'It needs 2-3 network confirmations and may take up to 30 minutes.'],
            ['title' => 'Can I lose my money?', 'details' => 'It is always the possibility in the sphere of investment. However, in our case, the probability is relatively low and with the help of a fund manager its almost risk-free  when you’re investing through our platform']

        ];

        foreach ($packages as $item) {
            if (Faq::where('title', '=', $item['title'])->first() === null) {
                Faq::create([
                    'title' => $item['title'],
                    'details'  => $item['details'],
                ]);
            }
        }
    }
}
