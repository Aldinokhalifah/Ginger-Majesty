<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="{{ asset('images/logo .jpg') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FinAi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body {
            /* height: 100%; */
            margin: 0;
            padding: 0;
        }
        .chat-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 8rem);
            max-height: calc(100vh - 4rem);
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
            padding: 1rem;
        }
        .chat-input-container {
            position: sticky;
            bottom: 0;
            background-color: white;
            border-top: 1px solid #e8b127;
            padding: 1rem;
            margin-top: auto;
            z-index: 10;
        }
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        .chat-messages::-webkit-scrollbar-track {
            background: #f7fafc;
        }
        .chat-messages::-webkit-scrollbar-thumb {
            background-color: #cbd5e0;
            border-radius: 3px;
        }
        .user-message {
            background-color: #fcca4b;
            border-radius: 18px 18px 0 18px;
            margin-left: auto;
            text-align: right;
        }
        .bot-message {
            background-color: #f1f5f9;
            border-radius: 18px 18px 18px 0;
        }
        .analysis-result {
            background-color: #f8fafc;
            border-left: 4px solid #f59e0b;
            border-radius: 6px;
        }
    </style>
</head>
<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition duration-200 ease-in-out lg:relative lg:flex z-50">
        <x-sidenav/>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full">
        <div class="flex-1 p-4 flex flex-col h-full">
            <!-- Header with menu button -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-center text-amber-600 flex-1">FinAi</h1>
                <button id="menuButton" class="lg:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg flex-1 flex flex-col chat-container">
                <div class="chat-messages" id="chatMessages">
                    <div class="bot-message p-3 mb-4 max-w-lg">
                        <p>Halo! Saya adalah FinAi. Saya dapat membantu menganalisis data Anda.</p>
                        <p class="text-sm text-gray-600 mt-2">Contoh permintaan:</p>
                        <ul class="text-sm text-gray-600 list-disc pl-5">
                            <li>Analisa pemasukkan dan pengeluaran saya di bulan ini</li>
                            <li>Apakah ada saran untuk saya agar dapat mengurangi pengeluaran?</li>
                        </ul>
                    </div>
                </div>
                
                <div class="chat-input-container">
                    <div class="chat-input flex gap-2">
                        <input 
                            type="text" 
                            id="messageInput" 
                            class="flex-1 border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-amber-500" 
                            placeholder="Ketik pesan Anda..."
                        >
                        <button 
                            id="sendButton" 
                            class="bg-amber-500 hover:bg-amber-600 text-white rounded-lg px-6 py-2 transition-colors"
                        >
                            Kirim
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chatMessages');
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.getElementById('sendButton');
            const menuButton = document.getElementById('menuButton');
            const sidebar = document.getElementById('sidebar');
            
            // Add transition classes for smooth animation
            sidebar.classList.add('transition-transform', 'duration-300', 'ease-in-out');
            
            menuButton.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                // Add overlay for mobile
                if (!sidebar.classList.contains('-translate-x-full')) {
                const overlay = document.createElement('div');
                overlay.className = 'fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300 z-40';
                overlay.onclick = () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.remove();
                };
                document.body.appendChild(overlay);
                    } else {
                const overlay = document.querySelector('.bg-opacity-50');
                if (overlay) overlay.remove();
                    }
            });
            
            // Function to add a message to the chat
            function addMessage(content, isUser, isAnalysis = false) {
                if (!content) return; // Add this check
                
                const messageDiv = document.createElement('div');
                const classes = ['p-3', 'mb-4', 'max-w-lg'];
                
                if (isUser) {
                    classes.push('user-message', 'ml-auto');
                } else {
                    classes.push('bot-message');
                }
                
                if (isAnalysis) {
                    classes.push('analysis-result', 'w-full', 'max-w-full');
                }
                
                messageDiv.classList.add(...classes);
                
                if (isAnalysis) {
                    messageDiv.innerHTML = `<h3 class="font-bold mb-2">Hasil Analisis:</h3>${content ? content.replace(/\n/g, '<br>') : ''}`;
                } else {
                    messageDiv.textContent = content || 'No content available';
                }
                
                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            
            // Function to send message
            function sendMessage() {
                const message = messageInput.value.trim();
                if (!message) return;
                
                // Add user message to chat
                addMessage(message, true);
                messageInput.value = '';
                
                // Show typing indicator
                const typingDiv = document.createElement('div');
                typingDiv.classList.add('bot-message', 'p-3', 'mb-4', 'animate-pulse');
                typingDiv.id = 'typingIndicator';
                typingDiv.textContent = 'Sedang mengetik...';
                chatMessages.appendChild(typingDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                
                // Send to server
                fetch('{{ route("chatbot.message") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 405) {
                            throw new Error('Method not allowed. Please check route configuration.');
                        }
                        throw new Error(`Terjadi Kesalahan Pada Jaringan`);
                        console.log(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const typingIndicator = document.getElementById('typingIndicator');
                    if (typingIndicator) {
                        typingIndicator.remove();
                    }
                    
                    if (data.error) {
                        addMessage('Error: ' + data.message, false, false);
                    } else {
                        addMessage(data.response, false, data.isAnalysis);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const typingIndicator = document.getElementById('typingIndicator');
                    if (typingIndicator) {
                        typingIndicator.remove();
                    }
                    addMessage(`Maaf, terjadi kesalahan: ${error.message}`, false, false);
                });
            }
            // Event listeners
            sendButton.addEventListener('click', sendMessage);
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        });
    </script>
</body>
</html>