<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbox</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Chat icon */
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

        /* Chatbox */
        #chatbox {
            position: fixed;
            bottom: 80px;
            right: 15px;
            width: 280px;
            background: white;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            display: none;
            z-index: 9999;
            overflow: hidden;
        }

        #chatbox header {
            background: #007bff;
            color: white;
            padding: 8px;
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
            font-size: 14px;
            border-bottom: 1px solid #ddd;
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

        /* Gợi ý tin nhắn */
        .chat-suggestions {
            display: flex;
            flex-wrap: wrap;
            padding: 5px;
            gap: 4px;
            justify-content: center;
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

        /* Input chat */
        .chat-input {
            display: flex;
            padding: 5px;
            gap: 5px;
            align-items: center;
        }

        #messageInput {
            flex: 1;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        #sendBtn {
            cursor: pointer;
            background: #007bff;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<!-- Chat icon -->
<div id="chatIcon">💬</div>

<!-- Chatbox -->
<div id="chatbox">
    <header>
        Chat hỗ trợ 
        <button id="closeChat">&times;</button>
    </header>
    <div id="messages"></div>

    <!-- Gợi ý tin nhắn -->
    <div class="chat-suggestions">
        <span class="suggestion" onclick="setMessage('Trạng thái  bàn 1')">🪑 Bàn 1</span>
        <span class="suggestion" onclick="setMessage('Doanh thu tổng')">📊 Doanh thu</span>
        <span class="suggestion" onclick="setMessage('Doanh thu ngày 12-03-2025')">📅 Ngày 12-03</span>
    </div>

    <div class="chat-input">
        <input type="text" id="messageInput" placeholder="Nhập tin nhắn...">
        <button id="sendBtn">Gửi</button>
    </div>
</div>

<script>
$(document).ready(function(){
    // Hiển thị / Ẩn chatbox khi nhấn icon
    $("#chatIcon").click(function(){
        $("#chatbox").toggle();
    });

    $("#closeChat").click(function(){
        $("#chatbox").hide();
    });

    function loadMessages() {
        $.get("{{ route('chat.layTinNhan') }}", function(data) {
            $('#messages').html('');
            data.forEach(msg => {
                let className = msg.ten === "Bạn" ? "user" : "bot";
                $('#messages').append(`<p class="message ${className}"><strong>${msg.ten}:</strong> ${msg.noi_dung}</p>`);
            });
        });
    }

    $("#sendBtn").click(function(){
        var message = $("#messageInput").val();
        if(message.trim() === '') return;

        $.post("{{ route('chat.gui') }}", {
            _token: "{{ csrf_token() }}",
            nguoi_dung_id: 1,
            noi_dung: message
        }, function(response) {
            $("#messages").append(`<p class="message user"><strong>Bạn:</strong> ${message}</p>`);
            if(response.phan_hoi) {
                $("#messages").append(`<p class="message bot"><strong>Bot:</strong> ${response.phan_hoi}</p>`);
            }
            $("#messageInput").val('');
        });
    });

    loadMessages();
    setInterval(loadMessages, 5000);
});

// Đặt câu hỏi gợi ý vào ô nhập
function setMessage(text) {
    document.getElementById('messageInput').value = text;
}
</script>

</body>
</html>
