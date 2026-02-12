<?php
$soap_result = null;
$soap_error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['soap_id'])) {
    try {
        $options = array(
            'location' => 'http://localhost/API/api/sabon_server.php',
            'uri' => 'http://localhost/API/api/'
        );
        $client = new SoapClient(null, $options);
        $id = intval($_POST['soap_id']);
        $result = $client->getUserById($id);
        
        if ($result) {
            $soap_result = $result;
        } else {
            $soap_error = "User not found with ID: $id";
        }
    } catch (SoapFault $e) {
        $soap_error = "SOAP Error: " . $e->getMessage();
    } catch (Exception $e) {
        $soap_error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#64748b',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-800">
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <header class="mb-10 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">User Management System</h1>
        </header>
        <?php if (isset($_GET['status'])): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $_GET['status'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> text-center">
                <?php echo $_GET['status'] === 'success' ? 'Action completed successfully!' : 'An error occurred.'; ?>
            </div>
        <?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="mb-4 pb-2 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">agiw_api (WEB)</h2>
                    <p class="text-sm text-gray-400">Standard HTML Form</p>
                </div>
                <form action="api/agiw.php" method="POST" class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="username" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition"
                            placeholder="Enter new username">
                    </div>
                    <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Add User
                    </button>
                </form>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="mb-4 pb-2 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">pahinga_api (REST)</h2>
                        <p class="text-sm text-gray-400">JSON Data via Fetch Fetch</p>
                    </div>
                    <button onclick="loadUsers()" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 px-2 py-1 rounded transition">Refresh</button>
                </div>
                
                <div id="rest-output" class="h-64 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                    <p class="text-gray-400 text-center italic mt-10">Click dito para sa users...</p>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <button onclick="loadUsers()" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Load Users via REST
                    </button>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="mb-4 pb-2 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">sabon_api (SOAP)</h2>
                    <p class="text-sm text-gray-400">SOAP Client Request</p>
                </div>
                
                <form method="POST" class="space-y-4">
                    <div>
                        <label for="soap_id" class="block text-sm font-medium text-gray-700 mb-1">User ID</label>
                        <div class="flex gap-2">
                            <input type="text" name="soap_id" id="soap_id" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition">
                            <button type="submit" 
                                class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200 min-h-[100px]">
                    <?php if ($soap_result): ?>
                        <div class="text-sm">
                            <p class="mb-1"><span class="font-bold text-gray-600">ID:</span> <?php echo htmlspecialchars($soap_result['id']); ?></p>
                            <p class="mb-1"><span class="font-bold text-gray-600">Username:</span> <?php echo htmlspecialchars($soap_result['username']); ?></p>
                            <p class="mb-1"><span class="font-bold text-gray-600">Created:</span> <?php echo htmlspecialchars($soap_result['created_at']); ?></p>
                        </div>
                    <?php elseif ($soap_error): ?>
                        <p class="text-red-500 text-sm"><?php echo htmlspecialchars($soap_error); ?></p>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm text-center italic">Dito po siya makikita</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        async function loadUsers() {
            const output = document.getElementById('rest-output');
            output.innerHTML = '<p class="text-gray-500 text-center animate-pulse">Loading...</p>';
            try {
                const response = await fetch('api/pahinga.php');
                const users = await response.json();
                if (users.length === 0) {
                    output.innerHTML = '<p class="text-gray-500 text-center">No users found.</p>';
                    return;
                }
                let html = '';
                users.forEach(user => {
                    html += `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-full">#${user.id}</span>
                                <span class="font-medium text-gray-700">${user.username}</span>
                            </div>
                            <span class="text-xs text-gray-400">${new Date(user.created_at).toLocaleDateString()}</span>
                        </div>
                    `;
                });
                output.innerHTML = html;
            } catch (error) {
                console.error('Error:', error);
                output.innerHTML = '<p class="text-red-500 text-center text-sm">Failed to load users.</p>';
            }
        }
    </script>
</body>
</html>
