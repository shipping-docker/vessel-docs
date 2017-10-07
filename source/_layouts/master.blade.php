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
            <div class="nav-wrap clearfix">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">Vessel</a>
                </div>
                <div id="navbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a class="github-icon" href="https://github.com/shipping-docker/vessel">
                                <svg aria-labelledby="simpleicons-github-icon" role="img" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="#3b4c52" d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="section-label">
                <h1>@if (! is_null($page->title)) {{ $page->title }} @else About @endif</h1>
            </div>
        </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-md-3 toc">
                    <nav class="toc-nav">
                        <h3>Usage</h3>
                        <a href="/docs/get-started" class="nav-item b undec pad @if($page->getFilename() == 'get-started') active @endif">Getting Started</a>
                        <a href="/docs/everyday-usage" class="nav-item b undec pad @if($page->getFilename() == 'everyday-usage') active @endif">Everyday Usage</a>
                        <a href="/docs/logging" class="nav-item b undec pad @if($page->getFilename() == 'logging') active @endif">Logging</a>

                        <h3 class="mtop">Docker</h3>
                        <a href="/docs/installing-docker" class="nav-item b undec pad @if($page->getFilename() == 'installing-docker') active @endif">Installing Docker</a>
                        <a href="/docs/docker-usage" class="nav-item b undec pad @if($page->getFilename() == 'docker-usage') active @endif">Docker Usage</a>

                        <h3 class="mtop">Learn More</h3>
                        <a href="/docs/learn-more" class="nav-item b undec pad @if($page->getFilename() == 'learn-more') active @endif">🐳 Free Resources</a>

                        <h3 class="mtop">Contribute</h3>
                        <a class="nav-item b undec pad" href="https://github.com/shipping-docker/vessel">
                                <svg style="max-width:12px; position: relative; top: 6px;" aria-labelledby="simpleicons-github-icon" role="img" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="#3b4c52" d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg> Vessel
                            </a>
                        <a href="https://github.com/shipping-docker/vessel-docs" class="nav-item b undec pad"><svg aria-labelledby="simpleicons-github-icon" role="img" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="max-width:12px; position: relative; top: 6px;"><path fill="#3b4c52" d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg> Vessel Docs</a>
                    </nav>
                </div>
                <div class="col-md-9 content">
                    @yield('body')
                </div>
            </div>
        </div>
        <footer>
            <div class="container tac">
                <div class="col-md-12">
                    <p>A <a href="https://serversforhackers.com/shipping-docker">Shipping Docker</a> Project</p>
                    <p class="sm">&copy; {{ date('Y') }} - Fideloper LLC</p>
                </div>
            </div>
        </footer>
        <script src="/js/main.js"></script>
    </body>
</html>
