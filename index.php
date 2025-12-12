<?php
 
 require_once('includes/header.php');
?>


    <!-- Hero Section -->
    <section class="hero" style="background-image: url('assets/world-map-background.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <h1>It's more than<br>just a trip</h1>
        <div class="search-bar">
            <div class="search-field">
                <span class="search-icon">✈</span>
                <input type="text" placeholder="From where?">
            </div>
            <div class="search-field">
                <span class="search-icon">✈</span>
                <input type="text" placeholder="Where to?">
            </div>
            <div class="search-field">
                <span class="search-icon">📅</span>
                <input type="text" placeholder="Depart - Return">
            </div>
            <div class="search-field">
                <span class="search-icon">👤</span>
                <input type="text" placeholder="1 adult">
            </div>
            <a href="search-flight.html" class="btn btn-primary">Search</a>
        </div>
    </section>

    <!-- Flight Deals Section -->
    <section class="container section">
        <div class="section-header">
            <h2>Find your next adventure with these flight deals</h2>
            <a href="search-flight.html" class="section-link">All →</a>
        </div>
        <div class="grid grid-3">
            <div class="card">
                <img src="assets/shanghai-bund.png" alt="The Bund, Shanghai" style="width: 100%; height: 397px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                <div style="padding: 16px 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
                        <h3 style="margin: 0; flex: 1;">The Bund, Shanghai</h3>
                        <span style="font-size: 18px; font-weight: 600; color: var(--grey-600); width: 72px; text-align: right;">$598</span>
                    </div>
                    <p style="color: var(--grey-400); margin: 0; font-size: 16px; line-height: 1.364;">China's most international city</p>
                </div>
            </div>
            <div class="card">
                <img src="assets/sydney-opera.png" alt="Sydney Opera House" style="width: 100%; height: 397px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                <div style="padding: 16px 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
                        <h3 style="margin: 0; flex: 1;">Sydney Opera House, Sydney</h3>
                        <span style="font-size: 18px; font-weight: 600; color: var(--grey-600); width: 72px; text-align: right;">$981</span>
                    </div>
                    <p style="color: var(--grey-400); margin: 0; font-size: 16px; line-height: 1.364;">Take a stroll along the famous harbor</p>
                </div>
            </div>
            <div class="card">
                <img src="assets/kyoto-temple.png" alt="Kōdaiji Temple" style="width: 100%; height: 397px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                <div style="padding: 16px 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
                        <h3 style="margin: 0; flex: 1;">Kōdaiji Temple, Kyoto</h3>
                        <span style="font-size: 18px; font-weight: 600; color: var(--grey-600); width: 72px; text-align: right;">$633</span>
                    </div>
                    <p style="color: var(--grey-400); margin: 0; font-size: 16px; line-height: 1.364;">Step back in time in the Gion district</p>
                </div>
            </div>
        </div>
        <!-- Kenya Card (Full Width) -->
        <div class="card" style="margin-top: 40px;">
            <img src="assets/kenya-savanna.png" alt="Tsavo East National Park" style="width: 100%; height: 397px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
            <div style="padding: 16px 24px;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
                    <h3 style="margin: 0; flex: 1;">Tsavo East National Park, Kenya</h3>
                    <span style="font-size: 18px; font-weight: 600; color: var(--grey-600); width: 72px; text-align: right;">$1,248</span>
                </div>
                <p style="color: var(--grey-400); margin: 0; font-size: 16px; line-height: 1.364;">Named after the Tsavo River, and opened in April 1984, Tsavo East National Park is one of the oldest parks in Kenya. It is located in the semi-arid Taru Desert.</p>
            </div>
        </div>
    </section>

    <!-- Unique Places to Stay Section -->
    <section class="container section">
        <div class="section-header">
            <h2>Explore unique places to stay</h2>
            <a href="#" class="section-link">All →</a>
        </div>
        <div class="grid grid-3">
            <div class="card">
                <img src="assets/maldives-atolls.png" alt="Maldives" style="width: 100%; height: 397px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                <div style="padding: 16px 24px;">
                    <h3 style="margin: 0 0 4px 0;">Stay among the atolls in Maldives</h3>
                    <p style="color: var(--grey-400); margin: 0; font-size: 16px; line-height: 1.364;">From the 2nd century AD, the islands were known as the 'Money Isles' due to the abundance of cowry shells, a currency of the early ages.</p>
                </div>
            </div>
            <div class="card">
                <img src="assets/morocco-ourika.png" alt="Morocco" style="width: 100%; height: 397px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                <div style="padding: 16px 24px;">
                    <h3 style="margin: 0 0 4px 0;">Experience the Ourika Valley in Morocco</h3>
                    <p style="color: var(--grey-400); margin: 0; font-size: 16px; line-height: 1.364;">Morocco's Hispano-Moorish architecture blends influences from Berber culture, Spain, and contemporary artistic currents in the Middle East.</p>
                </div>
            </div>
            <div class="card">
                <img src="assets/mongolia-yurt-559dae.png" alt="Mongolia" style="width: 100%; height: 397px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                <div style="padding: 16px 24px;">
                    <h3 style="margin: 0 0 4px 0;">Live traditionally in Mongolia</h3>
                    <p style="color: var(--grey-400); margin: 0; font-size: 16px; line-height: 1.364;">Traditional Mongolian yurts consists of an angled latticework of wood or bamboo for walls, ribs, and a wheel.</p>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-top: 24px;">
            <a href="#" class="btn btn-primary">Explore more stays</a>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="container section" style="padding: 64px;">
        <div class="section-header" style="justify-content: center; margin-bottom: 24px;">
            <h2>What Tripma users are saying</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px;">
            <div style="display: flex; gap: 16px; padding: 16px;">
                <img src="assets/avatar-yifei-chen.png" alt="Yifei Chen" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                <div style="flex: 1;">
                    <div style="margin-bottom: 12px;">
                        <h4 style="margin: 0; font-size: 18px; font-weight: 600; color: var(--grey-600);">Yifei Chen</h4>
                        <p style="margin: 0; font-size: 18px; color: var(--grey-600);">Seoul, South Korea | April 2019</p>
                        <div style="margin-top: 8px;">★★★★★</div>
                    </div>
                    <p style="margin: 0; font-size: 18px; color: var(--grey-900); line-height: 1.364;">What a great experience using Tripma! I booked all of my flights for my gap year through Tripma and never had any issues. When I had to cancel a flight because of an emergency, Tripma support helped me read more...</p>
                </div>
            </div>
            <div style="display: flex; gap: 16px; padding: 16px;">
                <img src="assets/avatar-kaori-yamaguchi.png" alt="Kaori Yamaguchi" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                <div style="flex: 1;">
                    <div style="margin-bottom: 12px;">
                        <h4 style="margin: 0; font-size: 18px; font-weight: 600; color: var(--grey-600);">Kaori Yamaguchi</h4>
                        <p style="margin: 0; font-size: 18px; color: var(--grey-600);">Honolulu, Hawaii | February 2017</p>
                        <div style="margin-top: 8px;">★★★★☆</div>
                    </div>
                    <p style="margin: 0; font-size: 18px; color: var(--grey-900); line-height: 1.364;">My family and I visit Hawaii every year, and we usually book our flights using other services. Tripma was recommened to us by a long time friend, and I'm so glad we tried it out! The process was easy and read more...</p>
                </div>
            </div>
            <div style="display: flex; gap: 16px; padding: 16px;">
                <img src="assets/avatar-anthony-lewis.png" alt="Anthony Lewis" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                <div style="flex: 1;">
                    <div style="margin-bottom: 12px;">
                        <h4 style="margin: 0; font-size: 18px; font-weight: 600; color: var(--grey-600);">Anthony Lewis</h4>
                        <p style="margin: 0; font-size: 18px; color: var(--grey-600);">Berlin, Germany | April 2019</p>
                        <div style="margin-top: 8px;">★★★★★</div>
                    </div>
                    <p style="margin: 0; font-size: 18px; color: var(--grey-900); line-height: 1.364;">When I was looking to book my flight to Berlin from LAX, Tripma had the best browsing experiece so I figured I'd give it a try. It was my first time using Tripma, but I'd definitely recommend it to a friend and use it for read more...</p>
                </div>
            </div>
        </div>
    </section>
<?php
 
 require_once('includes/footer.php');
?>

