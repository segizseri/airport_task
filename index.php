<?php 

$flights = flights();

$searchRoute = searchRoute($flights, 'VKO');

$routes = getRoute();

$longRoute = longRoute($routes);


echo json_encode($longRoute);


function getRoute()
{
	$flights = flights();
	$result = [];
	$duplicateKey = [];
	foreach ($flights as $key => $flight) {
		foreach ($flights as $childKey => $childFlight) {
			if ($flight['to'] === $childFlight['from']) {
				if (strtotime($flight['arrival']) < strtotime($childFlight['depart'])) {
					$flight['arrival'] = $childFlight['arrival'];
					$duplicateKey[] = $childKey;
				}
			}
		}
		if (!in_array($key, $duplicateKey)) {
			$result[] = $flight;	
		}
	}
	return $result;
}

function longRoute($flights): array
{
	$route = [];


	foreach ($flights as $key => $flight) {
		$arrival = strtotime($flight['arrival']);
		$depart = strtotime($flight['depart']);

		$differentTime = ABS($arrival - $depart);
		$route[$key] = $differentTime;
	}
	if (empty($route)) {
		return $route;
	}

	$key = array_search(max($route), $route);

	return $flights[$key];
}

function searchRoute($flights, $city = null)
{
	$result = [];
	foreach ($flights as $key => $flight) {
		if ($flight['from'] === $city || $flight['to'] === $city) {
			$result[] = $flight;
		}
	}
	return $result;
}

function flights()
{
	return [
	[
		'from' => 'VKO',
		'to' => 'DME',
		'depart' => '01.01.2020 12:44',
		'arrival' => '01.01.2020 13:44'
	],
	[
		'from' => 'DME',
		'to' => 'JFK',
		'depart' => '02.01.2020 23:00',
		'arrival' => '03.01.2020 11:44'
	],
	[
		'from' => 'DME',
		'to' => 'HKT',
		'depart' => '01.01.2020 13:40',
		'arrival' => '01.01.2020 22:22'
	]		
];
}

?>