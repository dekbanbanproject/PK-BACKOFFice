/// <reference path="../../../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { Model } from "./types";
import { ReportViewerCmd } from "../../api/ReportViewerCmd";
export declare type DocumentViewerProps = {
    dispatchViewerCmd: (cmd: ReportViewerCmd) => void;
//@ts-ignore
//@ts-ignore
    onClick: JSX.EventHandler<MouseEvent>;
};
export declare class View extends Component<DocumentViewerProps, Model> {
    constructor();
    render(): JSX.Element;
}
