/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { PropertyEditorProps } from '@grapecity/core-ui';
export declare type CalloutLineEditorLocalization = {
    lineTypes: {
        none: string;
        noneTitle: string;
        simple: string;
        simpleTitle: string;
        corner: string;
        cornerTitle: string;
    };
};
export declare type CalloutLineEditorProps = PropertyEditorProps & CalloutLineEditorLocalization;
export declare class CalloutLineEditor extends Component<CalloutLineEditorProps, any> {
    render(): JSX.Element;
}
