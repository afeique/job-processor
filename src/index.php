<?php

// Kickstart the framework
$f3=require('lib/base.php');
$f3->set('DB', new DB\SQL(
    'mysql:host=db;port=3306;dbname=db',
    'root',
    ''
));
header('Content-Type: application/json');
$f3->route('GET /',
    function($f3) {
        $data = $f3->get('DB')->exec('SELECT * FROM jobs');
        echo json_encode($data);
    }
);
$f3->route('GET /process',
    function($f3) {
        $job = $f3->get('DB')->exec('SELECT * FROM jobs WHERE processor_id IS NULL ORDER BY priority DESC LIMIT 1');
        $f3->get('DB')->exec('UPDATE jobs SET processor_id='. rand(1,10) .', finished_at=NOW() WHERE id='. $job[0]['id']);
        $job[0]['finished_at'] = date('Y-m-d H:i:s');
        echo json_encode($job[0]);

    }
);
$f3->route('GET /process/@id',
    function($f3) {
        $job_id = intval($f3->get('PARAMS.id'));
        if ($job_id) {
            $f3->get('DB')->exec('UPDATE jobs SET processor_id='. rand(1,10) .', finished_at=NOW() WHERE id='. $job_id);
            $job = $f3->get('DB')->exec('SELECT * FROM jobs WHERE id='. $job_id);
            echo json_encode($job);
        }

    }
);
$f3->route('GET /job',
    function($f3) {
        $job = $f3->get('DB')->exec('SELECT * FROM jobs WHERE processor_id IS NULL ORDER BY priority DESC LIMIT 1');
        echo json_encode($job);
    }
);
$f3->route('GET /job/@id',
    function($f3) {
        $job = $f3->get('DB')->exec('SELECT * FROM jobs WHERE id=?', $f3->get('PARAMS.id'));
        echo json_encode($job);
    }
);
$f3->route('POST /job',
    function($f3) {
        $user_id = intval($f3->get('POST.user_id'));
        $priority = intval($f3->get('POST.priority'));
        $cmd = $f3->get('POST.cmd');
        if (!$priority)
            $priority = 0;
        if (!$user_id || !$cmd)
            return;
        $f3->get('DB')->exec('INSERT INTO jobs (`priority`, `cmd`) VALUES (?,?)', [$priority, $cmd]);
        $f3->get('DB')->exec('INSERT INTO user_jobs (`user_id`,`job_id`) VALUES (?,LAST_INSERT_ID())', $user_id);
    }
);
$f3->route('GET /users',
    function($f3) {
        $data = $f3->get('DB')->exec('SELECT * FROM users');
        echo json_encode($data);
    }  
);
$f3->route('GET /user/@id',
    function($f3) {
        $user_jobs = $f3->get('DB')->exec('SELECT uj.user_id, u.name, uj.job_id, j.* FROM users u 
            LEFT JOIN user_jobs uj ON u.id=uj.user_id
            LEFT JOIN jobs j ON uj.job_id=j.id
            WHERE u.id=?', $f3->get('PARAMS.id'));
        $user = [
            'user_id' => $user_jobs[0]['user_id'],
            'name' => $user_jobs[0]['name'],
            'jobs' => []
        ];
        foreach ($user_jobs as $job) {
            unset($job['user_id']);
            unset($job['name']);
            if ($job['id'])
                $user['jobs'][] = $job;
        }
        echo json_encode($user);
    }
);
$f3->route('POST /register',
    function($f3) {
        $name = htmlentities($f3->get('POST.name'));
        $f3->get('DB')->exec('INSERT INTO users (`name`) VALUES (?)', $name);
    }
);

$f3->run();
