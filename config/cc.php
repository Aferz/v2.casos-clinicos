<?php

use App\Services\EvaluationCriteria\BooleanEvaluationCriterion;
use App\Services\EvaluationCriteria\NumericEvaluationCriterion;
use App\Services\Features\BibliographiesFeature;
use App\Services\Features\CommentsFeature;
use App\Services\Features\EvaluationCommentsFeature;
use App\Services\Features\HighlightFeature;
use App\Services\Features\ImagesFeature;
use App\Services\Features\LikesFeature;
use App\Services\Features\ShareFeature;
use App\Services\Fields\StringField;
use App\Services\Fields\TextField;

return [

    /*
    |--------------------------------------------------------------------------
    | Application configuration
    |--------------------------------------------------------------------------
    |
    | Configuration:
    |   ["service_id"]    int    Saned's service ID.
    |   ["lab_name"]      string Laboratory name.
    |
    */

    'app' => [
        'service_id' => 1,
        'lab_name' => 'Grünenthal',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication configuration
    |--------------------------------------------------------------------------
    |
    | Configuration:
    |   ["password_min_length"]    int Minimum characters for a valid password.
    |   ["password_max_length"]    int Maximum characters for a valid password.
    |
    */

    'auth' => [
        'password_min_length' => 8,
        'password_max_length' => 40,
    ],

    /*
    |--------------------------------------------------------------------------
    | "Directory" page configuration
    |--------------------------------------------------------------------------
    |
    | Configuration:
    |   ["clinical_cases_per_page"]    int    The number of clinical cases listed per page.
    |   ["card_title_field_name"]      string The clinical case field name that will hydrate the card's title.
    |   ["card_body_field_name"]       string The clinical case field name that will hydrate the card's body.
    */

    'directory' => [
        'clinical_cases_per_page' => 9,
        'card_title_field_name' => 'title',
        'card_body_field_name' => 'introduction',
    ],

    /*
    |--------------------------------------------------------------------------
    | "My cases" page configuration
    |--------------------------------------------------------------------------
    |
    | Configuration:
    |   ["clinical_cases_per_page"]    int    The number of clinical cases listed per page.
    |   ["title_field_name"]           string The clinical case field name that will hydrate the row's title.
    */

    'clinical_cases_list' => [
        'clinical_cases_per_page' => 15,
        'title_field_name' => 'title',
    ],

    /*
    |--------------------------------------------------------------------------
    | Clinical Case "detail" page configuration
    |--------------------------------------------------------------------------
    |
    | Configuration:
    |   ["title_field_name"]     string The clinical case field name that will hydrate title.
    |   ["author_field_name"]    string The clinical case field name that will hydrate author.
    */

    'clinical_case_detail_page' => [
        'title_field_name' => 'title',
        'author_field_name' => 'author',
    ],

    /*
    |--------------------------------------------------------------------------
    | Clinical Case form fields configuration
    |--------------------------------------------------------------------------
    |
    | Configuration of the clinical case form fields.
    |
    | Configuration:
    |   [fieldName]                     array  The name of the field.
    |       ["type"]                    string The underlying class of the field.
    |       ["migration"]               string (optional) Migration configuration.
    |           ["type"]                string (optional) Column type.
    |           ["args"]                string (optional) Arguments for the column.
    |           ["nullable"]            string (optional) Set the column nullable.
    |       ["factory"]                 string (optional) Factory configuration.
    |           ["property"]            string (optional) Faker property to be used.
    |           ["method"]              string (optional) Faker method to be used.
    |           ["args"]                string (optional) Faker method args to be used.
    |           ["value"]               string (optional) Set a raw value.
    |       ["render"]                  string (optional) Factory configuration.
    |           ["container_class"]     string (optional) Classes that will be applied to the field's container.
    |           ["attributes"]          array  (optional) Extra attributes appended to the field's HTML element.
    |               [key]               string (optional) Extra attribute name,
    |               [value]             string (optional) Extra attribute value.
    |       ["validation"]              array  (optional) Validation configuration.
    |           ["draft"]               string (optional) Rules applied when creating/updating a cinical case draft.
    |           ["send"]                string (optional) Rules applied when submitting a clinical case.
    */

    'fields' => [
        'title' => [
            'type' => StringField::class,
            'validation' => [
                'draft' => 'required|string|max:255',
            ],
        ],

        'author' => [
            'type' => StringField::class,
        ],

        'keywords' => [
            'type' => StringField::class,
        ],

        'background' => [
            'type' => TextField::class,
        ],

        'physical_test' => [
            'type' => TextField::class,
        ],

        'introduction' => [
            'type' => TextField::class,
        ],

        'diagnosis' => [
            'type' => TextField::class,
        ],

        'treatment' => [
            'type' => TextField::class,
        ],

        'evolution' => [
            'type' => TextField::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Clinical case evaluation configuration
    |--------------------------------------------------------------------------
    |
    | Configuration about how a clinical case is evaluated.
    |
    | Configuration:
    |   ["min_to_allow_publication"]    int    Minimum evaluations to consider a clinical case publishable.
    |   ["criteria"]                    array  Set of fields which will be used for evaluate the clinical case.
    |       [criterionName]             string Criterion name.
    |           ["type"]                string The underlying class of the evaluation criterion.
    |   ["defaults"]                    array  Default values for the available evaluation criteria.
    |       [criterionClass]            string (optional) Criterion class name.
    |           [key]                   string (optional) Configuration key name.
    |               [value]             mixed  (optional) The value of the key.
    */

    'evaluation' => [
        'min_to_allow_publication' => 1,

        'criteria' => [
            'originality' => [
                'type' => NumericEvaluationCriterion::class,
            ],

            'cientific-quality' => [
                'type' => NumericEvaluationCriterion::class,
            ],

            'redaction' => [
                'type' => NumericEvaluationCriterion::class,
            ],

            'complexity' => [
                'type' => NumericEvaluationCriterion::class,
            ],
        ],

        'defaults' => [
            BooleanEvaluationCriterion::class => [
                'theme' => 'buttons',
            ],

            NumericEvaluationCriterion::class => [
                'theme' => 'stars',
                'min' => 1,
                'max' => 5,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature flags
    |--------------------------------------------------------------------------
    */

    'features' => [

        /*
        |--------------------------------------------------------------------------
        | Form bibliographies
        |--------------------------------------------------------------------------
        |
        | Add bibliographies to the clinical case's form.
        |
        | Configuration:
        |   ["class"]      string  The underlying class for the feature.
        |   ["enabled"]    boolean Turn on/off the feature.
        |   ["min"]        int     Minimum of bibliographies to accept the clinical case submission. 0 means no limit.
        |   ["max"]        int     Maximum of bibliographies to accept the clinical case submission. 0 means no limit.
        |   ["rules"]      string  Set of rules applied to *each* bibliography individualy.
        */

        'bibliographies' => [
            'class' => BibliographiesFeature::class,
            'enabled' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Form images
        |--------------------------------------------------------------------------
        |
        | Add images to the clinical case's form.
        |
        | Configuration:
        |   ["class"]                 string  The underlying class for the feature.
        |   ["enabled"]               boolean Turn on/off the feature.
        |   ["min"]                   int     Minimum of images to accept the clinical case submission.
        |   ["max"]                   int     Maximum of images to accept the clinical case submission.
        |   ["accept"]                int     Mime types allowed for the HTML input file.
        |   ["rules"]                 int     Set of rules applied to *each* image individualy.
        |   ["rules_descriptions"]    string  Set of rules applied to *each* image description individualy.
        */

        'images' => [
            'class' => ImagesFeature::class,
            'enabled' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Clinical case comments
        |--------------------------------------------------------------------------
        |
        | Add a comments section for each clinical case.
        |
        | Configuration:
        |   ["class"]                       string  The underlying class for the feature.
        |   ["enabled"]                     boolean Turn on/off the feature.
        |   ["comments_per_page"]           int     Number of comments per page.
        |   ["replies_per_page"]            int     Number of replies per comment per page.
        |   ["seconds_between_comments"]    int     Number of seconds between comments or replies.
        */

        'comments' => [
            'class' => CommentsFeature::class,
            'enabled' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Clinical case likes
        |--------------------------------------------------------------------------
        |
        | Add a likable system for clinica cases.
        |
        | Configuration:
        |   ["class"]      string  The underlying class for the feature.
        |   ["enabled"]    boolean Turn on/off the feature.
        */

        'likes' => [
            'class' => LikesFeature::class,
            'enabled' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Clinical case sharing
        |--------------------------------------------------------------------------
        |
        | Add a the ability to share a clinical case.
        |
        | Configuration:
        |   ["class"]      string  The underlying class for the feature.
        |   ["enabled"]    boolean Turn on/off the feature.
        */

        'share' => [
            'class' => ShareFeature::class,
            'enabled' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Clinical case highlighting
        |--------------------------------------------------------------------------
        |
        | Add a the ability to highlight a clinical case.
        |
        | Configuration:
        |   ["class"]      string  The underlying class for the feature.
        |   ["enabled"]    boolean Turn on/off the feature.
        */

        'highlight' => [
            'class' => HighlightFeature::class,
            'enabled' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Evaluation Comments
        |--------------------------------------------------------------------------
        |
        | Add a comment field for evaluation criteria fields.
        |
        | Configuration:
        |   ["class"]      string  The underlying class for the feature.
        |   ["enabled"]    boolean Turn on/off the feature.
        |   ["rules"]      int     Set of rules applied to *each* comment individualy.
        */

        'evaluation_comments' => [
            'class' => EvaluationCommentsFeature::class,
            'enabled' => true,
        ],
    ],
];
