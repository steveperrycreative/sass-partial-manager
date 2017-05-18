# Sass Partial Manager
A simple command line tool for managing Sass partials.

I got fed up of manually creating Sass partials and wondering why my styles weren't updating in the browser due to forgetting to add the @import into my main styles file so I created a nice little command line tool to automatically do the job.

## Getting Started

First, download the SPM installer using Composer:

`composer global require steveperrycreative/sass-partial-manager`

Then add the ~/.composer/vendor/bin directory to your $PATH so that the spm executable can be located by your system.

The easiest way to do this is with the export command:

`export PATH=$PATH:~/.composer/vendor/bin`

The spm commands will now be available in your current working directory.

## Config

Add some details to your `composer.json` file as follows:

```
"spm_config": {
  "sass_directory": "wp-content/themes/themename/scss",
  "stylesheet_filename": "styles.scss"
}
```

- `sass_directory` is the full path to where you want your partials to be added (make sure these directories exist).
- `stylesheet_filename` is the name of your main Sass entry point file (defaults to styles.scss).

You can also override the stylesheet name on the command line.

## Creating A New Partial

It's early days so the structure of the partial directories is a bit opinionated towards how I work. This might change if anyone is interested enough.

The following example assumes that you are using WordPress with a theme called `spc`:

```
"spm_config": {
  "sass_directory": "wp-content/themes/spc/scss",
  "stylesheet_filename": "styles.scss"
}
```

`spm make:partial header` will create a new partial called `_header.scss` in a `components` folder:

`wp-content/themes/spc/scss/components/_header.scss`

And add `@import "components/header";` into your configured styles file:

`wp-content/themes/spc/scss/styles.scss`

If your styles file doesn't exist then it will be created. If it already exists then the import will be appended to the end of the file. If you try to create a partial which already exists then the command will stop running.

## Specifying A Component Type

`spm make:partial homepage --type="views"` will create a new partial called `_homepage.scss` in a `views` folder:

`wp-content/themes/spc/scss/views/_homepage.scss`

And add `@import "views/homepage";` into your configured styles file:

`wp-content/themes/spc/scss/styles.scss`

## Overriding The Sass Directory

You can specify a one-off `sass_directory` path when creating a partial:

`spm make:partial homepage --type="views" --sass_directory="some/other/location"` will create your partial in the specified location. This will also look for the styles file in that location.
