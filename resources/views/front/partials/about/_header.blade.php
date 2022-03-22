<header class="about--header">
    <div class="mt-container">
        <div class="mt-main-header-content">
            <div class="mt-header-writeup">
                <div>
                    <h1 class="heading1">About Jamborow</h1>
                    <h1 class="heading4">
                        According to World Bank data, 400 million of the adult African population are unbanked.
                        Jamborow are here to solve that problem.
                    </h1>
                    <h1 class="heading4">
                        Jamborow is  focused on Digitizing the KYC Data of the Village and creating credit footprint for Financial inclusion for the grassroots of Africa.
                    </h1>
                    <h1 class="heading4">
                        Our ecosystem is built around the Massive Unbanked population in Africa, 65% of this are adult population (Approx. 400M) which are represented by savings groups across Africa in forms of SACCO, AJO, ESUSU, SUSU etc.
                    </h1>
                </div>
            </div>
            <div class="mt-header-pattern-design">
                <img class="mt-header--pattern" src="{{ asset('img/about_header.svg') }}" uk-svg alt="">
                <a href="#modal-center" uk-toggle>
                    <img class="header-play-btn" src="{{ asset('img/play2.svg') }}" uk-svg alt="">
                </a>
                <div id="modal-center" class="uk-flex-top" uk-modal>
                    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">

                        <button class="uk-modal-close-default" type="button" uk-close></button>
                        <video src="{{ asset('videos/explainer.mp4') }}" controls playsinline uk-video></video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
