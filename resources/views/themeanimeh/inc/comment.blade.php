<div class="ah-frame-bg">
    <div class="flex flex-space-auto">
        <div class="fw-700 fs-16 color-yellow-2 flex flex-hozi-center">
            <span class="material-icons-round margin-0-5"> comment </span>Bình luận
        </div>
    </div>
    <div id="comments" class="margin-t-10">
        <div style="width: 100%; background-color: #fff">
            <div style="width: 100%; background-color: #fff" class="fb-comments" data-href="{{ $currentMovie->getUrl() }}" data-width="100%"
                 data-colorscheme="light" data-numposts="5" data-order-by="reverse_time" data-lazy="true"></div>
        </div>
    </div>
</div>
