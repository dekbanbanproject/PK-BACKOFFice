/// <reference path="../vendor/react/react.d.ts" />
//@ts-ignore
import { Component } from 'react';
import { ArticlesModel, ArticleNode, ArticleBead } from './types';
import PdfReportPlugin from '../plugin';
export declare type ArticlesProps = {
    navigate: (node: ArticleNode) => void;
    pligin: PdfReportPlugin;
};
export declare class Articles extends Component<ArticlesProps, ArticlesModel> {
    onFirstArticleBead(articleBead: ArticleBead): any;
    onLastArticleBead(articleBead: ArticleBead): any;
    setActiveBead(articleBead: ArticleBead): any;
    get currentArticleObjId(): string;
    set currentArticleObjId(id: string);
    private onItemClick;
    private renderArticles;
    render(): JSX.Element;
}
