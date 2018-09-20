/*
 * Copyright 2012-2018, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */
var tst = tst || {};

jQuery(function ($) {
    tst.adjustBackgroundImages = function (tstLoadedImages) {
        // Browse through all image IDs.
        for (var id in tstLoadedImages) {
            var image = tstLoadedImages[id];

            // Browse through all URLs.
            for (var i = 0; i < image.urls.length; i++) {
                var url = image.urls[i];

                // Find elements using this URL.
                var elements = $('*').filter(function () {
                    var $this = $(this);
                    var backgroundImage = $this.css('backgroundImage');

                    // Fix for avia-slideshow.
                    if (!backgroundImage || backgroundImage === 'none') {
                        backgroundImage = $this.attr('data-img-url');
                    }

                    return backgroundImage && (backgroundImage.indexOf(url) !== -1 || decodeURIComponent(backgroundImage).indexOf(url) !== -1) && ($(this).css('backgroundSize') === 'cover');
                });

                elements.each(function () {
                    var $this = $(this);

                    $this.css('background-position-x', image.focusPointX * 100 + '%');
                    $this.css('background-position-y', image.focusPointY * 100 + '%');
                });
            }
        }
    };
});

jQuery(document).on('ready', function () {
    if (typeof tstLoadedImages !== 'undefined') {
        tst.adjustBackgroundImages(tstLoadedImages);
    }
});
