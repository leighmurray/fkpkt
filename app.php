<?php
	
	require_once(__DIR__ . "/vendor/autoload.php");
	
	use Symfony\Component\HttpFoundation\Request;
	
	$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
	$twig = new Twig_Environment($loader, array(
	    'cache' => NULL,
	));	

	$request = Request::createFromGlobals();
	
	if ($request->query->has('url') && $request->query->get('url'))
	{
		$externalUrl = $request->query->get('url');
		$mercuryUrl = 'https://mercury.postlight.com/parser?url=';
		$client = new GuzzleHttp\Client();
		$mercuryResponse = $client->request('GET', sprintf('%s%s', $mercuryUrl, $externalUrl), [
		    'headers' => [
				'Content-Type' => 'application/json',
				'x-api-key' => '7R6VqwASR1ZRjiEJdNPbF4RSkaUZy7zmnGHeEWEs'
			]
		]);
		$mercuryBody = $mercuryResponse->getBody();
		$mercuryJson = json_decode($mercuryBody);

		echo $twig->render('base.twig', [
			'content' => $mercuryJson->content,
			'metaTitle' => $mercuryJson->title,
			'title' => $mercuryJson->title
		]);
	} else if ($request->request->has('markdown') && $request->request->get('markdown')) {
		$markdownText = $request->request->get('markdown');
		$Parsedown = new Parsedown();

		echo $Parsedown->text($markdownText); # prints: <p>Hello <em>Parsedown</em>!</p>
		
	} else {
		echo $twig->render('index.twig');
		exit();
	}
	

?>