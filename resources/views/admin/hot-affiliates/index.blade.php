<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hot Affiliates - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        
        .header h1 {
            color: #333;
            font-size: 28px;
        }
        
        .btn-logout {
            padding: 10px 20px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-logout:hover {
            background: #c0392b;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        thead {
            background: #667eea;
            color: white;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        tbody tr:hover {
            background: #f9f9f9;
        }
        
        tbody tr:last-child td {
            border-bottom: none;
        }
        
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }
        
        .pagination a:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .pagination .active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        
        .empty-state p {
            font-size: 18px;
            margin-top: 10px;
        }
        
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-card h3 {
            font-size: 32px;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            font-size: 14px;
            opacity: 0.9;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hot Affiliates</h1>
            <a href="{{ route('admin.hot-affiliates.logout') }}" class="btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('admin.hot-affiliates.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        
        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="stats">
            <div class="stat-card">
                <h3>{{ $hotAffiliates->total() }}</h3>
                <p>Total Affiliates</p>
            </div>
        </div>
        
        <div class="table-container">
            @if($hotAffiliates->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Count</th>
                            <th>User IP</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hotAffiliates as $affiliate)
                            <tr>
                                <td>{{ $affiliate->id }}</td>
                                <td>{{ $affiliate->first_name }}</td>
                                <td>{{ $affiliate->last_name }}</td>
                                <td>{{ $affiliate->email }}</td>
                                <td>{{ $affiliate->phone }}</td>
                                <td>{{ $affiliate->count ?? 1 }}</td>
                                <td>{{ $affiliate->user_ip ?? 'N/A' }}</td>
                                <td>{{ $affiliate->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.hot-affiliates.edit', $affiliate->id) }}" style="padding: 5px 10px; background: #3498db; color: white; text-decoration: none; border-radius: 3px; margin-right: 5px; font-size: 12px;">Edit</a>
                                    <form action="{{ route('admin.hot-affiliates.destroy', $affiliate->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this affiliate?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 5px 10px; background: #e74c3c; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="pagination">
                    {{ $hotAffiliates->links() }}
                </div>
            @else
                <div class="empty-state">
                    <p>No affiliates found</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>

