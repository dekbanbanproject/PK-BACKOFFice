import { ErrorDetails } from "./types";
/// <reference path="../../../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
/// <reference path="../../../../vendor/i18next.d.ts" />
//@ts-ignore
import i18next from "i18next";
declare type ErrorViewProps = {
    error: ErrorDetails;
    onDismiss: () => void;
    i18n: i18next.WithT;
};
declare type ErrorViewState = {
    expanded: boolean;
};
export declare class ErrorView extends Component<ErrorViewProps, ErrorViewState> {
    private static classModifierBase;
    private static styleMap;
    state: {
        expanded: boolean;
    };
    toggleExpand: () => void;
    render(): JSX.Element;
}
export {};
