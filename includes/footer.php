<div id="ai-chat-button" title="Buka Asisten AI">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
</div>

<div id="ai-chat-window">
    <div id="ai-chat-header">
        <span>Asisten REXBLOG AI</span>
        <button id="ai-chat-close">&times;</button>
    </div>
    <div id="ai-chat-messages">
        <div class="ai-chat-msg bot">
            <p>Halo! Saya asisten AI REXBLOG. Ada yang bisa saya bantu terkait penulisan postingan Anda?</p>
        </div>
    </div>
    <div id="ai-chat-footer">
        <input type="text" id="ai-chat-input" placeholder="Tanyakan sesuatu...">
        <button id="ai-chat-send">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </button>
    </div>
</div>

<style>
    #ai-chat-button {
        position: fixed;
        bottom: 25px;
        right: 25px;
        width: 60px;
        height: 60px;
        background: linear-gradient(145deg, #0d6efd, #0a58ca);
        color: white;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 28px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 9998;
        transition: transform 0.2s ease-out;
    }
    #ai-chat-button:hover {
        transform: scale(1.1);
        background: linear-gradient(145deg, #0b5ed7, #0a58ca);
    }

    #ai-chat-window {
        position: fixed;
        bottom: 100px;
        right: 25px;
        width: 370px;
        height: 500px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.25);
        display: none;
        flex-direction: column;
        z-index: 9999;
        overflow: hidden;
    }

    #ai-chat-header {
        background: #f8f9fa;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    #ai-chat-header span {
        font-weight: 600;
        color: #343a40;
    }
    #ai-chat-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6c757d;
    }

    #ai-chat-messages {
        flex-grow: 1;
        padding: 1rem;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .ai-chat-msg {
        max-width: 85%;
        padding: 0.75rem 1rem;
        border-radius: 18px;
    }
    .ai-chat-msg p {
        margin: 0;
        word-wrap: break-word;
    }
    .ai-chat-msg.bot {
        background: #e9ecef;
        color: #212529;
        border-bottom-left-radius: 4px;
        align-self: flex-start;
    }
    .ai-chat-msg.user {
        background: #0d6efd;
        color: white;
        border-bottom-right-radius: 4px;
        align-self: flex-end;
    }
    .ai-chat-msg.thinking {
        background: #e9ecef;
        color: #6c757d;
        border-bottom-left-radius: 4px;
        align-self: flex-start;
        font-style: italic;
    }

    #ai-chat-footer {
        padding: 1rem;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 0.5rem;
    }
    #ai-chat-input {
        flex-grow: 1;
        border: 1px solid #ced4da;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    #ai-chat-input:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    }
    #ai-chat-send {
        background: #0d6efd;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        flex-shrink: 0;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const chatButton = document.getElementById("ai-chat-button");
        const chatWindow = document.getElementById("ai-chat-window");
        const closeButton = document.getElementById("ai-chat-close");
        const sendButton = document.getElementById("ai-chat-send");
        const messageInput = document.getElementById("ai-chat-input");
        const messagesContainer = document.getElementById("ai-chat-messages");
        
        if (!chatButton || !chatWindow) return;

        chatButton.addEventListener("click", () => {
            chatWindow.style.display = "flex";
            chatButton.style.display = "none";
        });

        closeButton.addEventListener("click", () => {
            chatWindow.style.display = "none";
            chatButton.style.display = "flex";
        });

        sendButton.addEventListener("click", sendMessage);

        messageInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                sendMessage();
            }
        });

        async function sendMessage() {
            const messageText = messageInput.value.trim();
            if (messageText === "") return;

            addMessage("user", messageText);
            messageInput.value = "";
            
            const thinkingMsg = addMessage("thinking", "...");

            try {
                const response = await fetch("ai-chat.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ message: messageText })
                });
                
                messagesContainer.removeChild(thinkingMsg);

                if (!response.ok) {
                    addMessage("bot", "Maaf, ada error: " + response.statusText);
                    return;
                }

                const data = await response.json();
                
                if (data.reply) {
                    addMessage("bot", data.reply);
                } else {
                    addMessage("bot", "Maaf, saya tidak mengerti balasannya.");
                }

            } catch (error) {
                messagesContainer.removeChild(thinkingMsg);
                console.error("Fetch Error:", error);
                addMessage("bot", "Gagal terhubung ke server. Cek koneksi Anda.");
            }
        }

        function addMessage(sender, text) {
            const msgDiv = document.createElement("div");
            msgDiv.classList.add("ai-chat-msg", sender);
            
            text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
            text = text.replace(/\n/g, '<br>');

            msgDiv.innerHTML = `<p>${text}</p>`;
            messagesContainer.appendChild(msgDiv);

            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            return msgDiv;
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>