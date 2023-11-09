import { Model, Msg as ModelMsg } from "./types";
/// <reference path="../../../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
/// <reference path="../../../../vendor/i18next.d.ts" />
//@ts-ignore
import i18next from "i18next";
declare type ErrorPanelProps = {
    dispatch: (cmd: ModelMsg) => void;
    i18n: i18next.WithT;
};
declare type ErrorPanelState = Model & {
    dismissedErrors: number[];
};
export declare class View extends Component<ErrorPanelProps, ErrorPanelState> {
    constructor(props: ErrorPanelProps);
    private onDismissAll;
    private dismissError;
    render(): JSX.Element | null;
}
export {};
