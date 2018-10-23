<?xml version="1.0" encoding="UTF-8"?>

<schema xmlns="http://purl.oclc.org/dsdl/schematron">

    <ns prefix="example" uri="http://example.com"/>

    <pattern>
        <rule context="example:child">
            <assert test="* or normalize-space()">
                Must not be empty
            </assert>
        </rule>
    </pattern>

</schema>
