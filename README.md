* Como pasar o acceder a información de anteriores pasos en mi paso actual
* Para crear varios registros a la vez, en plan 5 artículos, creamos un paso del tipo tests/Functional/Acceptance/Product/Context/ProductCommandContext.php:47
* Para probar un listado por ejemplo, metemos varios registros en la base de datos o creamos un repositorio en memoria e inyectamos ese al contexto para estos casos?
* En el builder, ¿debo tener todos los atributos que tiene el modelo para poder modificarlo? tests/Functional/Common/EntityWithValue/Builder/ProductBuilder.php:11
* En tests/features/demo.feature:17 ¿comprobamos solo el esquema o debemos comprobar los datos que devuelve?
* En un create o update, ¿debemos comprobar los cambios en la base de datos?
