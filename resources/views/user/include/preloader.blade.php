<style>
    /* Preloader styles */
    .preloader {
        display: none; 
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        background: rgba(255, 255, 255, 0.8); 
        z-index: 9999; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center;
    }

    .preloader-icon {
        font-size: 40px; 
        color: black;
    }

    .preloader-text {
        font-size: 20px; 
        color: black; 
        font-weight: bold; 
        margin-top: 10px;
    }
</style>

<div id="preloader" class="preloader">
    <i class="fa fa-spinner fa-spin preloader-icon"></i>
    <div class="preloader-text">Version 1.1</div>
</div>

<script>
    // Function to show the preloader
    function showPreloader() {
        document.getElementById('preloader').style.display = 'flex';
    }

    // Function to hide the preloader
    function hidePreloader() {
        document.getElementById('preloader').style.display = 'none';
    }

    // Hide the preloader once the page is fully loaded
    window.onload = function() {
        hidePreloader();
    };
</script>
