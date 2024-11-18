<?php

namespace BerkahSoloWeb\EKinerja\App;

class View
{
    public static function renderWithLayout(string $view, array $model = [], string $css = '', string $js = '')
    {
        require __DIR__ . '/../Views/Layout/header.php';
        self::loadHeader($css);
        require __DIR__ . '/../Views/Layout/sidebar.php';
        require __DIR__ . '/../Views/' . $view . '.php';
        require __DIR__ . '/../Views/Layout/footer.php';
        self::loadFooter($js);
    }

    public static function renderSingleFile(string $view, $model = null)
    {
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    public static function redirect(string $url)
    {
        header("Location: $url");
        if (getenv("mode") != "test") {
            exit();
        }
    }

    private static function loadHeader(string $css)
    {
        echo  $css . PHP_EOL;
        echo '</head><body id="page-top">';
    }

    private static function loadFooter(string $js)
    {
        echo $js . PHP_EOL;
        echo '</body></html>';
    }
}
