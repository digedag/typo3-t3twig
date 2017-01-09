[Extensions](../extensions.md)

# TSParser

This extensions allows you to parse any field you have defined in TS.

## t3cObject

Creates output based on TypoScript.

```
    lib.testlink = TEXT
    lib.testlink {
        field = label
        typolink.parameter.field = pid
    }
```

```twig
    {{ t3cObject('lib.testlink', {'pid': 1, 'label': 'Wohoo'}) }}
```

## t3parseField

Renders an field with TS configuration.

```
plugin.tx_myplugin {
    confId {
        descriptionListCrop = TEXT
        descriptionListCrop.field = description
        descriptionListCrop.crop = 320 | ... | 1
    }
}
```

```twig
{{ item.row|t3parseField('descriptionListCrop') }}
```


If a flattened array of the record isn't still needed you can hand over an empty array to the extension.

```
plugin.tx_myplugin {
    confId {
        onlyText = TEXT
        onlyText.value = ExampleText
    }
}
```

```twig
{{ []|t3parseField('onlyText') }}
```