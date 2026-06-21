</main>

<section class="newsletter">
    <div>
        <p class="eyebrow">NEON ACCESS</p>
        <h2>Subscribe to our future newsletter.</h2>
        <p>Dapatkan update event, presale ticket, dan membership benefit langsung ke email kamu.</p>
    </div>
    <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Terima kasih! Newsletter berhasil disimpan.');">
        <input type="email" placeholder="Email address" required>
        <button>→</button>
    </form>
</section>

<footer class="site-footer">
    <div>
        <a class="brand footer-brand" href="<?= url('index.php'); ?>"><span class="brand-mark">FM</span><strong>FutureMusic</strong></a>
        <p>Cyber-modern platform for international music festival experience.</p>
    </div>
    <div>
        <h4>Useful Link</h4>
        <a href="<?= url('events.php'); ?>">Events</a>
        <a href="<?= url('artists.php'); ?>">Artists</a>
        <a href="<?= url('membership.php'); ?>">Membership</a>
    </div>
    <div>
        <h4>Contact Us</h4>
        <p>hello@futuremusic.test</p>
        <strong>+62 574 - 328 - 301</strong>
    </div>
    <div>
        <h4>Social</h4>
        <p>Instagram · TikTok · YouTube · X</p>
    </div>
</footer>

<button class="chat-launcher" data-chat-open>AI</button>
<div class="ai-chat" data-ai-chat>
    <div class="ai-chat-head">
        <strong>AI Music Assistant</strong>
        <button data-chat-close>×</button>
    </div>
    <div class="ai-chat-body" data-chat-body>
        <div class="bot">Halo! Mau cari event berdasarkan genre favoritmu?</div>
    </div>
    <form class="ai-chat-form" data-chat-form>
        <input type="text" placeholder="Tulis genre: EDM, Pop, Techno..." required>
        <button>Send</button>
    </form>
</div>

<script src="<?= url('assets/js/app.js'); ?>"></script>
</body>
</html>
