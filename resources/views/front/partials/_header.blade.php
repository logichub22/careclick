<header>
    <div class="mt-container">
        <div class="mt-main-header-content">
            <div class="mt-header-writeup">
                <div>
                    <h1 class="heading1">Jamborow</h1>
                    <h1 class="heading4">
                        Africa’s First Inclusive <br>
                        & Intelligent Fintech Platform
                    </h1>
                </div>
            </div>
            <div class="mt-header-pattern-design">
                <img class="mt-header--pattern" src="{{ asset('img/pattern.svg') }}" uk-svg alt="">
                <a href="#modal-center" uk-toggle>
                    <img class="header-play-btn" src="{{ asset('img/play_btn.svg') }}" uk-svg alt="">
                </a>
                <div id="modal-center" class="uk-flex-top" uk-modal>
                    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">

                        <button class="uk-modal-close-default" type="button" uk-close></button>
                        <video src="{{ asset('videos/jamborow.mp4') }}" controls playsinline uk-video></video>
                    </div>
                </div>
            </div>
        </div>
</header>
