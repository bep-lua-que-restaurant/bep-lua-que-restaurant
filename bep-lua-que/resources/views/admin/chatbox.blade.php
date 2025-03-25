<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbox</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #chatIcon {
            position: fixed;
            bottom: 15px;
            right: 15px;
            width: 50px;
            height: 50px;
            background: #007bff;
            color: white;
            text-align: center;
            line-height: 50px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 9999;
        }

        #chatbox {
            position: fixed;
            bottom: 80px;
            right: 15px;
            width: 300px;
            background: white;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            display: none;
            z-index: 9999;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        #chatbox header {
            background: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #closeChat {
            cursor: pointer;
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
        }

        #messages {
            max-height: 250px;
            overflow-y: auto;
            padding: 8px;
            flex: 1;
        }

        .message {
            margin-bottom: 5px;
            padding: 5px;
            border-radius: 5px;
        }

        .message.user {
            background: #007bff;
            color: white;
            text-align: right;
        }

        .message.bot {
            background: #f0f0f0;
            text-align: left;
        }

        .chat-suggestions {
            display: flex;
            flex-wrap: wrap;
            padding: 5px;
            gap: 5px;
            justify-content: center;
            border-top: 1px solid #ddd;
        }

        .suggestion {
            background: #f0f0f0;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .suggestion:hover {
            background: #007bff;
            color: white;
        }

        .chat-input {
            display: flex;
            padding: 8px;
            gap: 5px;
            border-top: 1px solid #ddd;
            align-items: flex-end;
        }

        #messageInput {
            min-height: 36px;
            max-height: 150px;
            resize: none;
            overflow-y: hidden;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            flex: 1;
            font-size: 14px;
            line-height: 20px;
        }

        #sendBtn {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div id="chatIcon">💬</div>

    <div id="chatbox" style="display: none;">
        <header>
            Chat hỗ trợ
            <button id="closeChat">&times;</button>
        </header>
        <div id="messages"></div>

        <div class="chat-suggestions">
            <span class="suggestion" onclick="setMessage('Trạng thái bàn 1')">🪑 Bàn 1</span>
            <span class="suggestion" onclick="setMessage('Doanh thu tổng')">📊 Doanh thu tổng</span>
            <span class="suggestion" onclick="setMessage('Doanh thu ngày 12-03-2025')">📅 Doanh thu ngày 12-03</span>
            <span class="suggestion" onclick="setMessage('Doanh thu từ 01-03-2025 đến 10-03-2025')">📆 Doanh thu từ ngày 01-03 đến 10-03</span>
            <span class="suggestion" onclick="setMessage('Món ăn yêu thích')">🍽️ Món yêu thích</span>
        </div>

        <div class="chat-input">
            <textarea id="messageInput" placeholder="Nhập tin nhắn..." oninput="autoResize(this)"></textarea>
            <button id="sendBtn">Gửi</button>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#chatIcon").click(function() {
                $("#chatbox").fadeToggle();
            });

            $("#closeChat").click(function() {
                $("#chatbox").fadeOut();
            });
        });
    </script>

</body>

</html>
