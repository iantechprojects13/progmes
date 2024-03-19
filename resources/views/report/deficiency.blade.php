<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- <div>{{ $tool}}</div> -->
    <div class="flex flex-row">
        <div class="mr-2">HEI Name: <b>{{ $tool->institution_program->institution->name }}</b></div>
        <div class="mr-2">Program: <b>{{ $tool->institution_program->program->program }}</b></div>
        <div class="mr-2">Evaluation Date: <b></b></div>
        <div class="mr-2">Occular Visit Date: <b></b></div>
        <div class="mr-2">Evaluated by: <b></b></div>
        <div class="mr-2">Reviewed by: <b></b></div>
        <div class="mr-2">Noted by: <b></b></div>
    </div>

    <table style="border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr>
                <th style="border: 1px solid black; padding: 5px; text-align: left;">Area</th>
                <th style="border: 1px solid black; padding: 5px">Minimum Requirement</th>
                <th style="border: 1px solid black; padding: 5px">Deficiencies</th>
                <th style="border: 1px solid black; padding: 5px">Recommendation</th>
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