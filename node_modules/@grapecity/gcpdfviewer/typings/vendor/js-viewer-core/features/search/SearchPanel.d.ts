import { Model, Msg, SearchResult } from './types';
import { ReportViewer } from '../../control';
/// <reference path="../../../../vendor/i18next.d.ts" />
//@ts-ignore
import i18n from 'i18next';
export declare const init: () => Model;
export declare const reducer: (msg: Msg, model: Model) => Model;
export declare const register: (viewer: ReportViewer, highlight: (result: SearchResult | null) => void, i18n: i18n.WithT) => void;
