<?php
// Direct Media Uploader for Padma Visual Editor
// Loads WordPress media uploader directly without extra steps

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load WordPress
if (!function_exists('wp_enqueue_script')) {
    require_once('../../../../../../wp-load.php');
}

// Redirect directly to media uploader
$media_url = admin_url('media-upload.php?type=image&TB_iframe=true&width=100%&height=100%');

// Custom send_to_editor function to handle the selection
?>
<!DOCTYPE html>
<html>
<head>
    <title>Media Uploader</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        #media-frame {
            width: 100%;
            height: 100vh;
            border: none;
        }
        /* Hide MarketPress cart and other unwanted elements */
        .mp_cart_widget_content,
        .mp_cart_widget,
        .marketpress-cart,
        .mp-cart,
        .floating-cart,
        .cart-widget,
        .woocommerce-cart,
        .cart-contents,
        .cart-icon,
        .header-cart,
        .mini-cart,
        .widget_shopping_cart,
        .shopping-cart-widget {
            display: none !important;
            visibility: hidden !important;
        }
    </style>
</head>
<body>
    <iframe id="media-frame" src="<?php echo $media_url; ?>"></iframe>
    
    <script>
        // Override send_to_editor function in the iframe
        window.addEventListener('message', function(event) {
            if (event.data && event.data.type === 'media_selected') {
                var imgUrl = event.data.url;
                var filename = event.data.filename;
                
                // Call parent callback
                if (window.parent && window.parent.imageUploaderCallback) {
                    window.parent.imageUploaderCallback(imgUrl, filename);
                }
                
                // Close the box
                if (window.parent && window.parent.closeBox) {
                    window.parent.closeBox('input-image', true);
                }
            }
        });
        
        // Wait for iframe to load and inject our script
        document.getElementById('media-frame').addEventListener('load', function() {
            try {
                var iframe = document.getElementById('media-frame');
                var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                var iframeWindow = iframe.contentWindow;
                
                // Override send_to_editor in the iframe
                iframeWindow.send_to_editor = function(html) {
                    // Extract image URL from HTML
                    var tempDiv = iframeDoc.createElement('div');
                    tempDiv.innerHTML = html;
                    
                    var imgUrl = '';
                    var img = tempDiv.querySelector('img');
                    if (img) {
                        imgUrl = img.getAttribute('src');
                    } else {
                        // Try to extract from link
                        var link = tempDiv.querySelector('a');
                        if (link) {
                            imgUrl = link.getAttribute('href');
                        }
                    }
                    
                    if (imgUrl) {
                        var filename = imgUrl.split('/').pop();
                        
                        // Call parent callback
                        if (window.parent && window.parent.imageUploaderCallback) {
                            window.parent.imageUploaderCallback(imgUrl, filename);
                        }
                        
                        // Close the box
                        if (window.parent && window.parent.closeBox) {
                            window.parent.closeBox('input-image', true);
                        }
                    }
                };
                
                // Hide unwanted elements in iframe
                var hideElements = [
                    '.mp_cart_widget_content',
                    '.mp_cart_widget',
                    '.marketpress-cart',
                    '.mp-cart',
                    '.floating-cart',
                    '.cart-widget',
                    '.woocommerce-cart',
                    '.cart-contents',
                    '.cart-icon',
                    '.header-cart',
                    '.mini-cart',
                    '.widget_shopping_cart',
                    '.shopping-cart-widget'
                ];
                
                hideElements.forEach(function(selector) {
                    var elements = iframeDoc.querySelectorAll(selector);
                    elements.forEach(function(el) {
                        el.style.display = 'none';
                        el.style.visibility = 'hidden';
                    });
                });
                
            } catch (e) {
                console.log('Cross-origin iframe access blocked, using fallback');
            }
        });
    </script>
</body>
</html>
