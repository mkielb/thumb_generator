# ThumbGenerator

Aplikacja stworzona w celach rekrutacyjnych do firmy ClickMeeting.

## Treść zadania
Należy zaimplementować w technologii PHP mechanizm tworzenia miniaturek obrazków z lokalnego dysku i zapisywania ich we wskazanym miejscu. Rozmiar dłuższego boku obrazka po przeskalowaniu to maksymalnie 150px. Miejscem docelowym zapisywanych plików, w zależności od wyboru użytkownika, może być folder na dysku, bucket w usłudze Amazon S3 lub folder w usłudze Dropbox.

## Uruchomienie

1\. Pobranie wymaganych paczek i ich instalacja:
```composer log
composer install
```

2\. Konfiguracja sposobów wysyłki miniaturki (plik [.env](.env)):
* serwer:
    * **SERVER_PATH** - katalog, gdzie zapisywane będą miniaturki np. "thumbs" (głównym katalogiem jest [public](public/)).
* Dropbox:
    * **DROPBOX_TOKEN** - token wymagany do połączenia się z API Dropboxa. Informacja, jak go wygenerować znajduje się [tutaj](https://dropbox.tech/developers/generate-an-access-token-for-your-own-account).
* Amazon S3:
    * **AWS_KEY** - klucz,
    * **AWS_SECRET** - sekret,
    * **AWS_VERSION** - wersja np. "latest",
    * **AWS_REGION** - region np. "us-west-2",
    * **AWS_BUCKET** - bucket w usłudze Amazon S3 np. "thumbs",
    * **AWS_ACL** - sposób dostępu dla wysyłanej miniaturki np. "public-read".

3\. Uruchomienie aplikacji (lokalny serwer Symfony):
* instalacja lokalnego serwera Symfony ([instrukcja](https://symfony.com/doc/current/setup/symfony_server.html)),
* uruchomienie serwera (w katalogu aplikacji):
```composer log
symfony server:start
```

## Opis rozwiązania

Aplikacja składa się z:
1. **[ThumbGenerator](src/Service/ThumbGenerator)** - klasa, która odpowiada za wygenerowanie miniaturki w sposób zgodny z zadaniem. Rezultatem tej klasy jest miniaturka w postaci obiektu **UploadedFile**, który przekazywany jest dalej do wysyłki,
2. **[ThumbUploader](src/Service/ThumbUploader)** - klasa, która odpowiada za wysyłkę miniaturki za pomocą określonej metody wysyłki ([serwer](src/Service/ThumbUploader/Method/Server.php), [Dropbox](src/Service/ThumbUploader/Method/CloudClient/DropboxClient.php), [Amazon S3](src/Service/ThumbUploader/Method/CloudClient/AWSClient.php)),
3. **[ThumbGenerator](src/Entity/ThumbGenerator.php)** oraz **[ThumbGeneratorType](src/Form/ThumbGeneratorType.php)** - klasy wykorzystywane do formularza,
4. **[ThumbGeneratorController](src/Controller/ThumbGeneratorController.php)** - główny kontroler, który wykorzystując powyższe klasy realizuje cel zadania.

## Wykorzystane paczki

1. **[dropbox-api](https://github.com/spatie/dropbox-api)** - klient umożliwiający połączenie się z chmurą Dropbox i wysyłkę pliku,
2. **[aws-sdk-php](https://github.com/aws/aws-sdk-php)** - klient umożliwiający połączenie się z chmurą Amazon S3 i wysyłkę pliku.