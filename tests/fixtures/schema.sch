<?xml version="1.0" encoding="UTF-8"?>

<schema xmlns="http://purl.oclc.org/dsdl/schematron">

    <ns prefix="example" uri="http://example.com"/>

    <pattern>
        <rule context="example:child">
            <assert test="* or normalize-space()">
                Element must not be empty
            </assert>
        </rule>
    </pattern>
    <pattern>
        <rule context="example:child/@attribute">
            <assert test="* or normalize-space()">
                Attribute must not be empty
            </assert>
        </rule>
    </pattern>

</schema>
