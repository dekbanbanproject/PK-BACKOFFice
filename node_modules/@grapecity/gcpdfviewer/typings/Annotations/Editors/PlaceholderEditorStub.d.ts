/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { PropertyEditorProps } from '@grapecity/core-ui';
/// <reference path="../../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
export declare type PlaceholderEditorStubEditorProps = PropertyEditorProps & {
    label: string | Function;
    in17n: i18n;
};
export declare class PlaceholderEditorStub extends Component<PlaceholderEditorStubEditorProps> {
    render(): JSX.Element;
}
