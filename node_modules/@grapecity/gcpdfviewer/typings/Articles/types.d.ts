export declare type ArticleBead = {
    objId: string;
    threadObjId: string;
    pageNumber: number;
    rectangle: number[];
    isFirst: boolean;
    isLast: boolean;
    next: ArticleBead;
    prev: ArticleBead;
};
export declare type ArticleNode = {
    objId: string;
    title: string;
    subject: string;
    creator: string;
    firstBead: ArticleBead;
};
export declare type ArticlesModel = {
    articles: ArticleNode[] | null;
    currentThreadObjId?: string;
};
export declare type ArticlesMsg = {
    type: 'reset';
};
