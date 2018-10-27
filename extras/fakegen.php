<?php
ini_set("allow_url_fopen", 1);

include_once "dbhelper.php";

function generateFakes($number) {
    $collegeCsv = array_map('str_getcsv', file('extras/colleges-list.csv'));
    array_walk($collegeCsv, function(&$a) use ($collegeCsv) {
        $a = array_combine($collegeCsv[0], $a);
    });
    array_shift($collegeCsv);

    $majorCsv = array_map('str_getcsv', file('extras/majors-list.csv'));
    array_walk($majorCsv, function(&$a) use ($majorCsv) {
        $a = array_combine($majorCsv[0], $a);
    });
    array_shift($majorCsv);

    $jobCsv = array_map('str_getcsv', file('extras/jobs-list.csv'));
    array_walk($jobCsv, function(&$a) use ($jobCsv) {
        $a = array_combine($jobCsv[0], $a);
    });
    array_shift($jobCsv);

    $employerCsv = array_map('str_getcsv', file('extras/employers-list.csv'));
    array_walk($employerCsv, function(&$a) use ($employerCsv) {
        $a = array_combine($employerCsv[0], $a);
    });
    array_shift($employerCsv);

    for ($i = 0; $i < $number; $i++) {
        $json = file_get_contents("https://randomuser.me/api/");
        $data = json_decode($json);
        $results = $data->results;
        $results = $results[0];

        $gender = 0;
        if ($results->gender != "male") {
            $gender = 1;
        }

        $user = new User($results->login->username, $results->login->password, ucfirst($results->name->first), ucfirst($results->location->state), ucfirst($results->name->last), $results->email, $gender, $results->phone, rand(0,1));
        $address = new Address(ucwords($results->location->street), ucfirst($results->location->city), $results->location->postcode, 1, 1);
        $numDegrees = rand(1, 3);
        for ($degreeNum = 0; $degreeNum < $numDegrees; $degreeNum++) {
            $college = $collegeCsv[array_rand($collegeCsv)];
            $major = $majorCsv[array_rand($majorCsv)];

            $degree[$degreeNum] = new EducationHistoryEntry(ucfirst($college[0]), rand(0, 3), ucwords(strtolower($major[1])), rand(1980, 2018), rand(1980, 2018));
        }

        $numJobs = rand(1, 3);
        for ($jobNum = 0; $jobNum < $numJobs; $jobNum++) {
            $employer = $employerCsv[array_rand($employerCsv)];
            $job = $jobCsv[array_rand($jobCsv)];
            $work[$jobNum] = new WorkHistoryEntry(ucfirst($employer[0]), ucfirst($job[0]), rand(1980, 2018), rand(1980, 2018));
        }

        $picture = $results->picture->large;

        registerUser($user, $address, $degree, $work, $picture, "");
    }
}