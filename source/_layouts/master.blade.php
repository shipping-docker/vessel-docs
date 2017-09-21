<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>
        <!-- Static navbar -->
        <nav class="navbar navbar-default navbar-static-top">
          <div class="container">
            <div class="navbar-header">
              <a class="navbar-brand" href="/">Vessel</a>
            </div>
            <div id="navbar">
              <ul class="nav navbar-nav navbar-right">
                <li><a href="https://github.com/shipping-docker/vessel">Github</a></li>
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-md-3 toc">
                    <nav class="toc-nav">
                        <a href="/docs/installation" class="nav-item b undec pad">Installation</a>
                        <a href="/docs/everyday-usage" class="nav-item b undec pad">Everyday Usage</a>
                        <a href="/docs/installing-docker" class="nav-item b undec pad">Installing Docker</a>
                        <a href="/docs/learn-more" class="nav-item b undec pad">🐳 Learn More</a>
                    </nav>
                </div>
                <div class="col-md-9 content">
                    @yield('body')
                </div>
            </div>
        </div>

    </body>
</html>
