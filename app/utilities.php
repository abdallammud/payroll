<?php 
function baseUri() {
    // Check if we are on HTTPS or HTTP
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    
    // Get the server name and the current path
    $host = $_SERVER['HTTP_HOST'];
    $path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    
    // Determine if it's a live environment or a local project directory
    if ($host === 'localhost' || $host === '127.0.0.1') {
        // For local development (e.g., subfolder)
        $baseUri = $protocol . $host . $path;
    } else {
        // For production (e.g., domain)
        $baseUri = $protocol . $host;
    }

    $GLOBALS['baseUri'] = $baseUri;

    return $baseUri;
}

function load_js_module() {
    if(isset($_GET['menu'])) {
        $menu = $_GET['menu'];
        if($menu == 'dashboard') {
            echo '<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
                <script src="assets/js/dashboard2.js"></script>';
        } else {
            echo '<script type="text/javascript" src="'.baseUri().'/assets/js/modules/'.$menu.'.js"></script>';
        }
        
    }
}
function json($array) {
    echo json_encode($array);
}



?>