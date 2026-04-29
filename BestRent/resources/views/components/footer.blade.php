<footer>
    <div class="container">
        <div class="row mb-4">
            
            <div class="col-md-4 footer-section mb-3 mb-md-0">
                <div class="footer-section-title">
                    <img src="{{ asset('images/best-rent-logo.png') }}" alt="Best-Rent" class="footer-brand-logo">
                    <span>Best-Rent</span>
                </div>
                <p>Modern autókölcsönzési platformunk megbízható és kedvező autóbérlési lehetőséget nyújt.</p>
            </div>

            
            <div class="col-md-4 footer-section mb-3 mb-md-0">
                <div class="footer-section-title">
                    <i class="bi bi-link-45deg"></i> Gyors Linkek
                </div>
                <ul class="footer-links">
                    <li><a href="/"><i class="bi bi-chevron-right"></i> Kezdőlap</a></li>
                    <li><a href="/cars"><i class="bi bi-chevron-right"></i> Autók</a></li>
                    @auth
                        <li><a href="/client/dashboard"><i class="bi bi-chevron-right"></i> Fiók</a></li>
                    @endauth
                </ul>
            </div>

            
            <div class="col-md-4 footer-section">
                <div class="footer-section-title">
                    <i class="bi bi-telephone"></i> Elérhetőség
                </div>
                <div class="footer-contact">
                    <div class="contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <p>+36 1 234 5678</p>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <p><a href="mailto:info@bestrent.com" class="footer-contact-link">info@bestrent.com</a></p>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <p>Budapest, Magyarország</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="footer-divider"></div>

        
        <div class="footer-bottom">
            <p>&copy; 2026 <strong>Best-Rent</strong>. Minden jog fenntartva. | 
                <a href="#" class="footer-bottom-link">Adatvédelmi Irányelv</a> | 
                <a href="#" class="footer-bottom-link">Felhasználási Feltételek</a>
            </p>
        </div>
    </div>
</footer>
