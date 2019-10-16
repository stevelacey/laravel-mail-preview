<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Mail Preview</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ route('mailpreview') }}">Mail Preview</a>
        </nav>

        <main role="main">
            <div class="row no-gutters">
                <div class="col-4 col-xl-3" style="height: calc(100vh - 3.5rem); overflow: auto">
                    <nav class="inbox nav flex-column bg-light">
                        @foreach($inbox as $entry)
                            <a href="{{ route('mailpreview.show', $entry->path) }}" class="
                                nav-link
                                {{ $email == $entry ? 'active bg-success' : '' }}
                                {{ $email == $entry ? 'text-white' : 'text-secondary' }}
                            ">
                                <div class="subject text-truncate">{{ $entry->subject }}</div>
                                <div class="row">
                                    <div class="col-lg-7 text-truncate">To: {{ $entry->to }}</div>
                                    <div class="col-lg-5 text-lg-right text-truncate">{{ $entry->datetime->diffForHumans() }}</div>
                                </div>
                            </a>
                        @endforeach
                    </nav>
                </div>

                <div class="col-8 col-xl-9 p-3" style="height: calc(100vh - 3.5rem);">
                    <h1 class="h3">{{ $email->subject }}</h1>

                    <dl class="row">
                        <dt class="col-2 col-lg-1">Date:</dt>
                        <dd class="col-10 col-lg-11 m-0">{{ $email->date }}</dd>
                        <dt class="col-2 col-lg-1">From:</dt>
                        <dd class="col-10 col-lg-11 m-0">{{ $email->from }}</dd>
                        <dt class="col-2 col-lg-1">To:</dt>
                        <dd class="col-10 col-lg-11 m-0">{{ $email->to }}</dd>
                    </dl>

                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="{{ route('mailpreview_html', $email->path) }}" class="nav-link text-secondary" target="preview">
                                HTML
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mailpreview_source', $email->path) }}" class="nav-link text-secondary" target="preview">
                                Source
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mailpreview_text', $email->path) }}" class="nav-link text-secondary" target="preview">
                                Text
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mailpreview_eml', $email->path) }}" class="nav-link text-secondary" target="preview">
                                Raw
                            </a>
                        </li>
                    </ul>

                    <iframe name="preview" class="border border-top-0 w-100" src="{{ route('mailpreview_html', $email->path) }}" style="height: calc(100vh - 15.5rem); min-height: 10rem;"></iframe>
                </div>
            </div>
        </main>

        <style>
            body {
                font-size: .875rem;
            }

            .vh-100 {
                height: 100vh;
            }

            .mh-100 {
                max-height: 100%;
            }

            .inbox .nav-link {
              border-left: .5rem solid white;
              padding-left: .5rem;
              border-color: #bbb;
            }

            .inbox .nav-link:visited {
              border-color: white;
            }

            .inbox .nav-link.active {
              border-color: #28a745;
            }

            .inbox .nav-link.active:focus, .inbox .nav-link.active:hover {
              border-color: #1e7e34;
            }
        </style>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
