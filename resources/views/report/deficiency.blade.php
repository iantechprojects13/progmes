<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: arial, sans-serif;
            font-size: 11pt;
        }
    </style>
</head>

<body>
    <div style="display: flex; flex-direction: row; width: 100%; text-align: center; align-items: center;">
        <div style="margin-right: 10px; display: inline-block;">
            <img src="assets/ched-logo.png" width="55" style="display: inline-block;">
        </div>
        <div style="display: inline-block;">
            <div>Republic of the Philippines</div>
            <div><i>Office of the President</i></div>
            <div><b>COMMISSION ON HIGHER EDUCATION</b></div>
        </div>
    </div>
    <div style="margin-top: 20px; width: 100%; text-align: center;">
        <b>DEFICIENCY REPORT</b>
    </div>
    <div style="margin-top: 20px;">
        Date:
    </div>
    <div style="margin-top: 20px;">
        <div><b>HEI Name:</b> {{ $tool->institution_program->institution->name }}</div>
        <div><b>Program:</b> {{ $tool->institution_program->program->program }}</div>
        <div><b>Data of Ocular Visits:</b> </div>
    </div>

    <table style="border-collapse: collapse; margin-top: 20px; font-size: 11pt;">
        <thead>
            <tr>
                <th style="border: 0.5px solid black; padding: 3px; text-align: left;">Area</th>
                <th style="border: 0.5px solid black; padding: 3px; text-align: left;">Minimum Requirement</th>
                <th style="border: 0.5px solid black; padding: 3px; text-align: left;">Deficiencies</th>
                <th style="border: 0.5px solid black; padding: 3px; text-align: left;">Recommendation</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tool->item as $item)
            <tr>
                <td style="border: 1px solid black; padding: 5px; vertical-align: text-top;">
                    {{ $item->criteria->area }}
                </td>
                <td style="border: 1px solid black; padding: 5px; vertical-align: text-top;">
                    {{ $item->criteria->minimumRequirement }}
                </td>
                <td style="border: 1px solid black; padding: 5px; vertical-align: text-top;">
                    {{ $item->findings }}
                </td>
                <td style="border: 1px solid black; padding: 5px; vertical-align: text-top;">
                    {{ $item->recommendations }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>