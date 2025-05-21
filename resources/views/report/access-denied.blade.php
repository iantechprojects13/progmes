<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ asset('assets/ched_logo.png') }}" type="image/x-icon" />
    <title>Report Access Denied</title>
    <style>
        @page {
            margin: 20px 96px 20px 96px;
        }
        body {
            font-family: arial, sans-serif;
            font-size: 11pt;
            margin-top: 0cm;
            margin-left: 0cm;
            margin-right: 0cm;
            margin-bottom: 0cm;
            padding-top: 130px;
            padding-bottom: 75px;
        }
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: fit-content;
            vertical-align: middle;
            color: black;
            text-align: center;
            line-height: 1.5cm;
            font-family: Georgia, "Times New Roman", Times, serif;
            border-bottom: 2px solid black;
        }
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 55px;
            color: black;
            text-align: center;
        }
        #ched {
            font-size: 11pt;
            margin-top: 0px;
            vertical-align: text-bottom;
            position: relative;
            top: 3px;
        }
        #roxi {
            font-size: 20pt;
            border-top: 1px solid black;
            vertical-align: text-top;
            z-index: 1;
            padding-top: 2px;
        }
        th {
            border: 0.5px solid black;
            text-align: left;
            padding: 10px auto 10px 5px;
        }
        td {
            border: 0.5px solid black;
            text-align: left;
            padding: 10px 5px 10px 5px;
            vertical-align: text-top;
        }
        .access-denied {
            font-size: 36pt;
            font-weight: bold;
            text-align: center;
            margin-top: 100px;
            color: #cc0000;
        }
    </style>
</head>
<body>
    <header>
        <div style="
                    display: flex;
                    flex-direction: row;
                    width: 100%;
                    text-align: center;
                    align-items: center;
                ">
            <div style="
                        margin-right: 25px;
                        display: inline-block;
                        position: relative;
                        top: 5px;
                    ">
                <img src="assets/ched-logo.png" width="85" style="display: inline-block" />
            </div>
            <div style="display: inline-block; position: relative; top: 12px">
                <div id="ched">COMMISSION ON HIGHER EDUCATION</div>
                <div id="roxi">REGIONAL OFFICE XI</div>
            </div>
            <div style="margin-left: 25px; display: inline-block">
                <img src="assets/bagong-pilipinas-logo.png" width="85" style="display: inline-block" />
            </div>
        </div>
    </header>
    <footer>
        <div style="
                    padding-top: 5px;
                    border-top: 2px solid black;
                    margin-bottom: 5px;
                ">
            <div style="font-weight: bold; font-size: 11pt">
                University of Southeastern Philippines Compound, Loyola St.,
                Bo. Obrero, Davao City
            </div>
            <div>
                www.ro11.ched.ph | chedro11@ched.gov.ph | (082) 295-3418
            </div>
        </div>
    </footer>
    <main>
        <div class="access-denied">
            Access Denied
        </div>
    </main>
</body>
</html>
