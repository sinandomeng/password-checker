<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Simple Password Checker</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.css">
    <style type="text/css">
        html {
            background: #f5f5f5;
        }
        .header-container {
            margin: 50px 50px 0px 50px;
        }
        .header-container h3 {
            text-align: center;
        }
        .register-container {
            display: flex;
        }
        .register-container .server-side {
            width: 100%;
            height: 100%;
            margin: 50px;
        }
        .register-container .server-side .not-bad { border-color: hsl(204, 86%, 53%); }
        .register-container .server-side .good { border-color: hsl(48,  100%, 67%); }
        .register-container .server-side .strong { border-color: hsl(141, 71%, 48%); }
        .notice-box .notice {
            color: #363636;
            display: block;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .notice-box ol {
            padding-left: 1em;
        }
        .notice-container {
            display: flex;
            justify-content: space-between;
            margin: 0px 50px 50px 50px;
        }
        .notice-container > div {
            width: 49%;
            height: 100%;
        }
    </style>
</head>

<body>
    <header class="header-container">
        <div class="box notice-box">
            <h3 class="title is-3">Password Analyzer Exam</h3>

            <h6 class="title notice">Requrements</h6>

            <ol>
                <li>Must be at least 8 characters long.</li>
                <li>Must have both upper and lower case letters.</li>
                <li>Must have letters and at least one number or symbol.</li>
            </ol>
        </div>
    </header>

    <section class="register-container">
        <div class="server-side">
            <div class="box">
                @if (Session::has('customError'))
                    <div class="notification is-danger">
                        <ul>
                            @foreach (Session::get('customError') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    @if (Session::has('passwordStrength'))
                        <div class="notification is-primary">
                            Your strong password is successfully saved.
                        </div>
                    @endif
                @endif

                <form method="post" action="/register" autocomplete="off">
                    @csrf()
    
                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input type="password" name="password" class="input {{ Session::get('passwordStrength') }}" />

                            @if (Session::has('passwordStrength'))
                                <small>Your password strength is <strong>{{ ucwords(str_replace('-', ' ', Session::get('passwordStrength'))) }}</strong></small>
                            @endif
                        </div>
                    </div>
                    
                    <button class="button is-link">Submit</button>
                </form>
            </div>
        </div>
    </section>

    <section class="notice-container">
        <div>
            <div class="box notice-box">
                <h6 class="title notice">Examples</h6>
                <ul class="">
                    <li>For Weak password strength, leave it blank or input a space.</li>
                    <li>For Not Bad password, it's not possible to trigger this as we need to input 8 character long first.</li>
                    <li>For Good, try "Password"</li>
                    <li>For Strong, try "P@ssword"</li>
                </ul>
                <br>
                <p>Feel free to pick your own password.</p>
            </div>

            <div class="box notice-box">
                <h6 class="title notice">Credentials</h6>
                <ul class="">
                    <li>DATABASE NAME: password_analyzer</li>
                    <li>DATABASE TABLE: password_analyzers</li>
                </ul>
            </div>
        </div>

        <div>
            <div class="box notice-box">
                <h6 class="title notice">Notice</h6>
                <p>Please allow me to share what I have in mind. As I understand, each every requirements returns a point.</p>

                <ul class="">
                    <li>1 point for 8 character long</li>
                    <li>1 point for having a lowercase and uppercase</li>
                    <li>1 point for numeric or symbol</li>
                </ul>
                
                <br>

                <p>Base upon the direction, "A password will be determined as good if it has two of the above characteristics while not bad if it has only one. <strong>If the password is not bad, good or strong, you can store it into a database.</strong>"</p>
                <p>If the user inputs <strong>"A1"</strong> as their password, that will be 2 points. 1 for the casing and another 1 for numeric/symbol. Programmatically, the system will give a marked of GOOD and you said it will be saved in the database. But that is not fair, since we have a validation rule of 8 characters long. And in a real world scenario, there is no such password of <strong>"A1"</strong> allowed to store in the database.</p>

                <br>

                <p>Another thing is to check wether the password already exist in the database. In real world scenario again, before you store a password in the database it must be encrypted. Once you encrypt a password, there will be no way to decrypt it. Unless, you made your own encryption tool but it will very slow since you have to decrypt all of the passwords first before you compare. Therefore, checking if the password already exist in the database is not scoped with this repo/project.</p>

                <br>

                <p>To make it short, this program will only store strong password.</p>
            </div>
        </div>
    </section>

    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</body>

</html>
