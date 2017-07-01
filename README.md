# Multi Photo Crop

Detect and crop multiple photos from a single image. Perfect for digitalizing your old paper photo albums.

### Prerequisites

In order to use the script [ImageMagick](http://www.imagemagick.org/script/index.php) has to be installed on your system. (If you're on a Mac I suggest using Homebrew `brew install imagemagick`).

### Installing

The easiest way to install the the script is by using composer.

```shell
composer global require wnx/multi-photo-crop
```

If the installation was successful you should be able to execute the following command:

```shell
multi-photo-crop --version
```

## Usage

After installing the package you can run the script like this:

```shell
multi-photo-crop run --images="/path/to/your/images/**/*.png" --output="~/Downloads/"
```

## Running the tests

There are some phpunit tests. You can run them with the following command.

```shell
phpunit
```

## Built With

* [MultiCrop](http://www.fmwconcepts.com/imagemagick/multicrop/) (not included in Repository)
* [Symfony Console](https://github.com/symfony/console)
* [Symfony Process](https://github.com/symfony/process)

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/stefanzweifel/multicrop-photos/tags).

## Authors

* **Stefan Zweifel** - *Initial work* - [stefanzweifel](https://github.com/stefanzweifel)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

The Multicrop Script has a special License. Read more [here](http://www.fmwconcepts.com/imagemagick/multicrop/).
