<?php
return array(

    'cars' => array(
        'vw' => array(
            'golf',
            'vento',
            't5',
        ),
        'mercedes' => array(
            'sl500',
            'c-coupe'
        ),
    ),
    'stuff' => array(
        'array' => array( 'anotherArray' => array(), ),
        'stdClass' => new \StdClass(),
        'constant' => \PDO::FETCH_BOUND, // 6
    )

);