<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Oops! Something went wrong</title>
<link rel="stylesheet" href="{DEFAULT_URL}view/defaults/exception/src/css/exception.css">
</head>
<style>	
	
</style>
<body>
<header style="background-color:BACKCOLOR">
    <h1>Oops! Something went wrong!</h1>
    <p>{ERROR_TYPE}</p>
    <p>{MESSAGE}</p>
</header>

<main>
    <div>
        <h3>File: {FILE}</h3>
        <h3>Line Number: {LINE}</h3>
    </div>
    <ul>
        {ERROR_DETAILS}
    </ul>
</main>
<footer>
    CHKN Framework
</footer>
</body>
</html>
