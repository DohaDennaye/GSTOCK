<?php include 'entete.php'; ?>

<div class="home-content">

    <h2> Assistant IA – Analyse intelligente du stock</h2><br/>

    <div class="chat-container">
        <div class="chat-box" id="chatBox">
            <div class="message ai">
                Bonjour 👋
                Je suis votre assistant intelligent spécialisé en **gestion de stock et prévision des ventes**.
                Posez-moi une question comme :
                <br>• <i>Quels articles risquent une rupture ?</i>
                <br>• <i>Analyse le stock actuel</i>
                <br>• <i>Que recommandes-tu aujourd’hui ?</i>
            </div>
        </div>

        <form id="chatForm" class="chat-input">
            <input type="text" id="userMessage" placeholder="Posez votre question..." required>
            <button type="submit">➤</button>
        </form>
    </div>
    <div style="text-align:center;font-size:13px;color:#777;margin-top:8px">
        💡 Exemple : "Prévois le stock pour la semaine prochaine"
    </div>

</div>

<?php include 'pied.php'; ?>

<style>
    .chat-container {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .12);
        max-width: 900px;
        margin: auto;
        display: flex;
        flex-direction: column;
        height: 70vh;
    }

    .chat-box {
        padding: 20px;
        overflow-y: auto;
        flex: 1;
    }

    .message {
        max-width: 75%;
        padding: 12px 16px;
        margin-bottom: 14px;
        border-radius: 14px;
        font-size: 14px;
        line-height: 1.5;
    }

    .message.ai {
        background: #f1f5f9;
        align-self: flex-start;
        border-left: 4px solid #0a2558;
    }

    .message.user {
        background: #0a2558;
        color: #fff;
        align-self: flex-end;
    }

    .chat-input {
        display: flex;
        border-top: 1px solid #ddd;
    }

    .chat-input input {
        flex: 1;
        padding: 15px;
        border: none;
        outline: none;
    }

    .chat-input button {
        background: #0a2558;
        color: #fff;
        border: none;
        padding: 0 22px;
        cursor: pointer;
        font-size: 18px;
    }
</style>

<script>
    const form = document.getElementById('chatForm');
    const chatBox = document.getElementById('chatBox');
    const input = document.getElementById('userMessage');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const msg = input.value;
        chatBox.innerHTML += `<div class="message user">${msg}</div>`;
        input.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;

        const res = await fetch('analyse_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'message=' + encodeURIComponent(msg)
        });

        const text = await res.text();
        chatBox.innerHTML += `<div class="message ai">${text}</div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>