/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { PropertyEditorProps } from '@grapecity/core-ui';
/// <reference path="../../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from 'i18next';
export declare type KeyValuePairEditorLocalization = {
    emptyName?: string;
    emptyValue?: string;
};
export declare type KeyValuePairPropertyEditorProps = PropertyEditorProps & KeyValuePairEditorLocalization & {
    keyPath?: string;
    valuePath?: string;
    in17n: i18n;
};
export declare class KeyValuePairEditor extends Component<KeyValuePairPropertyEditorProps> {
    render(): JSX.Element;
}
