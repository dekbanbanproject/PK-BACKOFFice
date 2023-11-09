/// <reference path="../../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
//@ts-ignore
import { PropertyEditorProps } from "@grapecity/core-ui";
/// <reference path="../../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from "i18next";
import { GcPdfViewer } from "../../GcPdfViewer";
import { LinkAnnotation } from "../AnnotationTypes";
export declare type LinkDestTypeEditorProps = PropertyEditorProps & {
    in17n: i18n;
};
export declare class LinkDestTypeEditor extends Component<LinkDestTypeEditorProps, any> {
    get originalNode(): LinkAnnotation;
    get viewer(): GcPdfViewer;
    render(): JSX.Element;
}
