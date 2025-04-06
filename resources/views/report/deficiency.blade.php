<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/cropped-ched_logo.png') }}" type="image/x-icon">
    <title>Deficiency Report</title>
    <style>
        @page {
            margin: 20px 38px 20 38px;
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
            font-family: Georgia, 'Times New Roman', Times, serif;
            border-bottom: 2px solid black;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 75px;
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

        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>

<body>
    <header>
        <div style="display: flex; flex-direction: row; width: 100%; text-align: center; align-items: center;">
            <div style="margin-right: 25px; display: inline-block; position: relative; top: 5px;">
                <img src="assets/ched-logo.png" width="85" style="display: inline-block;">
            </div>
            <div style="display: inline-block; position: relative; top: 12px">
                <div id="ched">COMMISSION ON HIGHER EDUCATION</div>
                <div id="roxi">REGIONAL OFFICE XI</div>
            </div>
            <div style="margin-left: 25px; display: inline-block;">
                <img src="assets/bagong-pilipinas-logo.png" width="85" style="display: inline-block;">
            </div>
        </div>
    </header>
    <footer>
        <div style="padding-top: 10px; border-top: 2px solid black; margin-bottom: 10px;">
            <div style="font-weight: bold; font-size: 12pt; margin-bottom: 3px;">
                University of Southeastern Philippines Compound, Loyola St., Bo. Obrero, Davao City
            </div>
            <div>www.ro11.ched.ph | chedro11@ched.gov.ph | (082) 295-3418</div>
        </div>
        <div class="pagenum"></div>
    </footer>
    <main>
        <div>
            <div style="width: 100%; text-align: center; margin-top: 20px;">
                <b>PROGRAM MONITORING</b>
            </div>
            <div style="width: 100%; text-align: center; margin-top: 8px;">
                <b>DEFICIENCY REPORT</b>
            </div>
            <div style="margin-top: 30px;">
                <div style="display: inline-block"><b>HEI Name:</b></div>
                <div style="display: inline-block;">{{ $tool->institution_program->institution->name }}</div>
                <br>

                <div style="display: inline-block; margin-top: 5px"><b>Program:</b></div>
                <div style="display: inline-block;">{{ $tool->institution_program->program->program }}</div>
                <br>

                <div style="display: inline-block; margin-top: 5px"><b>Monitoring/Evaluation Reference:</b></div>
                <div style="display: inline-block;">CMO {{ $tool->cmo->number }}, S. {{ $tool->cmo->series }}</div>
                <br>

                <div style="display: inline-block; margin-top: 5px"><b>Monitoring/Evaluation Date:</b></div>
                <div style="display: inline-block;">{{ date('F j, Y', strtotime($tool->evaluationDate)) }}</div>
                <br>
            </div>

            <table style="border-collapse: collapse; margin-top: 30px; font-size: 11pt;">
                <thead>
                    <tr>
                        <th>AREA</th>
                        <th>MINIMUM REQUIREMENT</th>
                        <th>DEFICIENCY</th>
                        <th>RECOMMENDATION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tool->item as $item)
                    <tr>
                        <td>{{ strip_tags($item->criteria->area) }}</td>
                        <td>{{ strip_tags($item->criteria->minimumRequirement) }}</td>
                        <td>{{ strip_tags($item->findings) }}</td>
                        <td>{{ strip_tags($item->recommendations) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Signatories -->
            <div style="margin-top: 40px; width: 100%; overflow: hidden;">
                <!-- Row 1 -->
                <!-- <div style="width: 100%; overflow: hidden; margin-bottom: 30px;">
                    <div style="float: left; width: 45%; page-break-inside: avoid; margin-left: 3%;">
                        <div>Conforme:</div>
                        <div style="margin: 8px 20px;">
                            <div>{{ $tool->conforme1 }}</div>
                            <div>{{ $tool->conforme1Title }}</div>
                        </div>
                    </div>
                    <div style="float: left; width: 45%; page-break-inside: avoid; margin-left: 4%;">
                        <div>Conforme:</div>
                        <div style="margin: 8px 20px;">
                            <div>{{ $tool->conforme2 }}</div>
                            <div>{{ $tool->conforme2Title }}</div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div> -->
                
                <!-- Row 2 -->
                <div style="width: 100%; overflow: hidden; margin-top: 40px;">
                    <div style="float: left; width: 31%; page-break-inside: avoid; margin-left: 3%;">
                        <div>Evaluated by:</div>
                        <div style="margin: 8px 20px;">
                            <div>{{ $tool->evaluatedBy }}</div>
                            <div>{{ $tool->evaluatedByTitle }}</div>
                        </div>
                    </div>
                    <div style="float: left; width: 31%; page-break-inside: avoid; margin-left: 4%;">
                        <div>Reviewed by:</div>
                        <div style="margin: 8px 20px;">
                            <div>{{ $tool->reviewedBy }}</div>
                            <div>{{ $tool->reviewedByTitle }}</div>
                        </div>
                    </div>
                    <div style="float: left; width: 31%; page-break-inside: avoid; margin-left: 3%;">
                        <div>Noted by:</div>
                        <div style="margin: 8px 20px;">
                            <div>{{ $tool->notedBy }}</div>
                            <div>{{ $tool->notedByTitle }}</div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
                
                <!-- Row 3 (only one item) -->
                <!-- <div style="width: 100%; overflow: hidden;">
                    <div style="float: left; width: 45%; page-break-inside: avoid; margin-left: 3%;">
                        <div>Noted by:</div>
                        <div style="margin: 8px 20px;">
                            <div>{{ $tool->notedBy }}</div>
                            <div>{{ $tool->notedByTitle }}</div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div> -->
            </div>
            
        </div>
    </main>
</body>

</html>