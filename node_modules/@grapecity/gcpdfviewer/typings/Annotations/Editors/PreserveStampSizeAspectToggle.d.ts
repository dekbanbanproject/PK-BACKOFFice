/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from "react";
import GcPdfViewer from "../..";
export declare type PreserveStampSizeAspectToggleProps = {
    viewer: GcPdfViewer;
};
export declare class PreserveStampSizeAspectToggle extends Component<PreserveStampSizeAspectToggleProps, any> {
    private static instance;
    static notifyKeepAspectRatioChanged(): void;
    componentDidMount(): void;
    componentWillUnmount(): void;
    render(): JSX.Element;
}
