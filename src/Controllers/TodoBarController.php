<?php

namespace TPaksu\TodoBar\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

class TodoBarController extends Controller
{

    public function getScripts()
    {
        return "<script src='https://cdn.jsdelivr.net/npm/vue'></script>
        <script type='text/javascript'>
        " . (file_exists($this->assetsPath("chunk-vendors.js")) ?
            file_get_contents($this->assetsPath("chunk-vendors.js")) : "") . "
        " . (file_exists($this->assetsPath("app.js")) ?
            file_get_contents($this->assetsPath("app.js")) : "") . "
            window.todoBarToken = '" . Session::token() . "';
        </script>";
    }

    public function getStyles()
    {
        return "<style>
        " . (file_exists($this->assetsPath("app.css")) ?
            file_get_contents($this->assetsPath("app.css")) : "") . "
        </style>";
    }

    public function getDrawer()
    {
        return "<div id='laravel-todobar'></div>";
    }

    public function getInjection()
    {
        return $this->getStyles() . $this->getDrawer() . $this->getScripts();
    }

    public function inject(Response $response)
    {
        $response->setContent(str_replace("</body>", "</body>" . $this->getInjection(), $response->getContent()));
    }

    public function assetsPath($file)
    {
        return implode(
            DIRECTORY_SEPARATOR,
            [__DIR__, "..", "..", "dist", $file]
        );
    }
}
