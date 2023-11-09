﻿﻿﻿# 更新履歴（日本語版）
このファイルには、GrapeCity PDF ビューワ（日本語版）のすべての更新履歴が記載されています。
なお、日本語版として動作を保証しているのは、本ファイルに記載のあるバージョンのみとなります。

更新履歴のフォーマットは、[「Keep a Changelog」](https://keepachangelog.com/ja/1.0.0/)を参考にしています。また、GrapeCity PDF ビューワのプロジェクトは、[「Semantic Versioning」](https://semver.org/lang/ja/spec/v2.0.0.html)に準拠しています。

# [3.1.4] - 2022年5月25日
### 追加
- サイドパネルにサイズ変更のハンドルを追加しました。
- invalidate メソッドを追加しました。ビューワのすべての視覚的要素を適切にレイアウト更新します。
- requiredSupportApiVersion プロパティを追加しました。PDF ビューワの現在のバージョンと互換性のある SupportApi の必要バージョンを取得します。
- supportApiVersion プロパティを追加しました。接続された SupportApi のバージョンがあれば取得します。
- gcPdfVersion プロパティを追加しました。接続された SupportApi で使用されている GcPdf ライブラリのバージョンがあれば取得します。
- 回転ハンドルを使用して、スタンプ注釈を回転できるようになりました。\
  なお、Shift キーを押すと、回転角度が90度の倍数になるよう制御できます。
- XFA フォームの印刷、送信、リセット、JavaScript アクション、ハイパーリンクに対応しました。
- PDF を保存する際、レイヤーの表示状態も保存できるようになりました。
- orientation プロパティを使用して、フォームフィールドのテキストの方向を変更できるようになりました。
- エディタにて、ツールバーボタンの機能を連続実行できるようになりました。\
  toolbarLayout に stickyBehavior プロパティを追加しました。連続実行できるようにするボタンのキーを持つ配列を設定します。
  ただし、連続実行できるのは、注釈／フォームエディタのツールバーボタンだけです。\
  連続実行できるボタンの全リストは次のとおりです：'edit-sign-tool', 'edit-text', 'edit-free-text', 'edit-ink', 'edit-square', 'edit-circle', 'edit-line', 'edit-polyline', 'edit-polygon', 'edit-stamp', 'edit-file-attachment',
  'edit-sound', 'edit-link', 'edit-redact', 'edit-widget-tx-field', 'edit-widget-tx-password',
  'edit-widget-tx-text-area', 'edit-widget-btn-checkbox', 'edit-widget-btn-radio',
  'edit-widget-btn-push', 'edit-widget-ch-combo', 'edit-widget-ch-list-box', 'edit-widget-tx-comb',
  'edit-widget-btn-submit', 'edit-widget-btn-reset', 'edit-erase-field'
```javascript
  // 例：四角形注釈、円注釈、線注釈、墨消し注釈のボタンを連続実行できるようにします。
  viewer.toolbarLayout.stickyBehavior = ["edit-square", "edit-circle", "edit-line", "edit-redact"];
```
- fieldsAppearance オプションを追加しました。ボタンフィールドのレンダリング方法を指定します。
```javascript  
  // 例1：プッシュボタンの外観に［Web］を設定します。
  var viewer = new GcPdfViewer("#root", { fieldsAppearance: { pushButton: "Web" } });
  // 例2：プッシュボタンの外観に［Predefined］を設定します。
  var viewer = new GcPdfViewer("#root", { fieldsAppearance: { pushButton: "Predefined" } });
```
- プログラムにて左サイドバーを非表示にできるようになりました。
```javascript
  // サイドバーを非表示にします。
  viewer.toggleSidebar(false);
  // 以下のコードでも実現できます。
  viewer.leftSidebar.hide();
```
- プログラムにてツールバーを表示／非表示にできるようになりました。
```javascript
  // 例：
  viewer.toggleToolbar(false);
```
- hideAnnotationPopups オプションを追加しました。このオプションを使用すると、すべての注釈ポップアップを非表示にできます。
```javascript
  // 例：
  var viewer = new GcPdfViewer("#root", { hideAnnotationPopups: true });
```
- 現在読み込んでいるドキュメントを閉じることができるようになりました。
```javascript
  // 例：
  await viewer.close();
```
- 次のイベントを追加しました：onBeforeAddAnnotation, onAfterAddAnnotation, onBeforeUpdateAnnotation, onAfterUpdateAnnotation,
  onBeforeRemoveAnnotation, onAfterRemoveAnnotation
```javascript
  // 例：
  viewer.onBeforeAddAnnotation.register(function(args) { console.log(args); });
```
  なお、BeforeAddAnnotation／BeforeUpdateAnnotation／BeforeRemoveAnnotation イベントはキャンセル可能です。
```javascript
  // イベントキャンセルの例：
  viewer.getEvent("BeforeAddAnnotation").register(function(args) { args.cancel = true; });
  viewer.getEvent("BeforeUpdateAnnotation").register(function(args) {
    args.cancel = true;
    viewer.repaint();
  });
```
- 独自のイベントを待ち受けし、トリガーできるようになりました。
```javascript
  // 独自のイベントを待ち受けする例：
  viewer.getEvent("CustomEvent").register(function(args) {
    console.log(args);
  });
  // 独自のイベントをトリガーする例：
  viewer.triggerEvent("CustomEvent", { arg1: 1, arg2: 2});
```
- open() メソッドに認証やその他の HTTP ヘッダーを指定できるようになりました。
```javascript
  // アクセス認証と独自のヘッダーを指定する例：
  viewer.open("http://example.com//pdfs/GetPdf?file=HelloWorld.pdf", { 
    headers: { 
      "Authorization": "Basic " + btoa(unescape(encodeURIComponent("USERNAME:PASSWORD"))),
      "CustomHeader": "Custom header value"
    }
  });
```
 - SupportApi のリクエストに認証やその他の HTTP ヘッダーを指定できるようになりました。
```javascript
  // アクセス認証と独自のヘッダーを指定する例：
  const viewer = new GcPdfViewer("#viewer", {
    supportApi: {
      apiUrl: "192.168.0.1/support-api",
      requestInit: { 
        headers: {
          "Authorization": "Basic " + btoa(unescape(encodeURIComponent("USERNAME:PASSWORD"))),
          "CustomHeader": "Custom header value"
        }
      }
    }
  });
```
- コンボボックスの設定項目に「編集を許可」を追加しました。
- フォームエディタの「タブの順序」の選択項目に「注釈配列」と「ウィジェット」を追加しました。

### 変更
- 接続している SupportApi のバージョンが 0.0.0.0（ソースからのビルド）の場合、バージョン不一致の警告を表示しないよう変更しました。


## [3.0.13] - 2022年2月9日
### 重大な変更
- ページ番号を使用するすべての API にて、0から始まるページインデックスを使用するようになりました。

### 追加
- セカンドツールバーに対応しました。
- メインメニューのツールバーに編集のためのセカンドツールバーを次のとおり追加しました：「テキストツール」「描画ツール」「添付ファイルとスタンプ」「フォームツール」「ページツール」\
  これにより、注釈編集モードやフォーム編集モードに切り替えることなく、ドキュメントを編集できるようになりました。\
  どのツールバーを有効にするかを制御するには、secondToolbarLayout プロパティを使用します。
  ```javascript
  // 例：セカンドツールバーのレイアウトを指定します。
  viewer.secondToolbarLayout = { "text-tools": ['edit-text', 'edit-free-text'] };
  ```
- 独自のセカンドツールバーを表示するための API を追加しました。
  ```javascript
  // 例：キー「custom-toolbar-key」にて独自のセカンドツールバーを作成します。
  var React = viewer.getType("React");
  var toolbarControls = [React.createElement("label", { style: {color: "white"} }, "独自のツールバー"),
  React.createElement("button", { onClick: () => { alert("アクションが実行されました。"); }, title: "Action title" }, "アクション")];
  // 独自のセカンドツールバーを「custom-toolbar-key」として登録します。
  viewer.options.secondToolbar = {
   render: function(toolbarKey) {
     if(toolbarKey === "custom-toolbar-key")
       return toolbarControls;
     return null;
   }
  };
  // 独自のセカンドツールバーを表示します。
  viewer.showSecondToolbar("custom-toolbar-key");
  ```
- タグ付き PDF の構造ツリーを表示する構造ツリーパネルを追加しました。
  ```javascript
  // 構造ツリーパネルを追加するには、addStructureTreePanel メソッドを使用します。
  const viewer = new GcPdfViewer(selector);
  viewer.addStructureTreePanel();
  await viewer.open("sample.pdf");
  ```
  ```javascript
  // 利用可能な構造ツリーデータを取得するには、structureTree プロパティを使用します。
  const viewer = new GcPdfViewer(selector);
  await viewer.open("sample.pdf");
  const structureTree = await viewer.structureTree;
  if(structureTree) {
   console.log(structureTree);
  }
  ```
- PDF レイヤーの表示／非表示を切り替えることができる、レイヤーパネルを追加しました。
  ```javascript
  // 例：
  viewer.addLayersPanel();
  ```
- レイヤーの設定を行う optionalContentConfig プロパティを追加しました。
  ```javascript
  // 例：ID が「13R」のレイヤー を非表示にします。
  const config = await viewer.optionalContentConfig;
  config.setVisibility("13R", false);
  viewer.repaint();
  ```
  ```javascript
  // 例：利用可能なレイヤーに関する情報をコンソールに表示します。
  const config = await viewer.optionalContentConfig;
  console.table(config.getGroups());
  ```
- サイドパネルを開く openPanel メソッドを追加しました。
  ```javascript
  // 例：
  const layersPanelHandle = viewer.addLayersPanel();
  viewer.open("house-plan.pdf").then(()=> {
    viewer.openPanel(layersPanelHandle);
  });
  ```
- サイドパネルを閉じる closePanel メソッドを追加しました。
  ```javascript
  // 例：
  viewer.closePanel();
  ```
- resetChanges メソッドを追加しました。すべての変更点を破棄し、ドキュメントを元の状態にリセットします。
  ```javascript
  // 例：
  await viewer.resetChanges();
  ```
- goToPage メソッドを追加しました。0から始まるインデックスによって指定されたページに移動します。
  ```javascript
  //例：最初のページに移動します。
  viewer.goToPage(0);
  ```
- setPageRotation(pageIndex, rotation) メソッドを追加しました。PDF の特定のページを回転させることができます。\
  このメソッドの使用には SupportApi が必要です。また、回転の指定に有効な値は、0／90／180／270度です。
  ```javascript
  // 例：1ページ目を180度回転します。
  await viewer.setPageRotation(0, 180);
  ```
- getPageRotation(pageIndex) メソッドを追加しました。指定したページの回転値を取得します。
  ```javascript    
  // 例：1ページ目の回転（度）を取得します。
  var rotation = viewer.getPageRotation(0);
  ```
- 新しいテーマとして Light テーマと Dark テーマを追加しました。
- requireTheme オプションを追加しました。このオプションを使用して組み込みの CSS テーマを適用することで、テーマスタイルがページヘッドに直接挿入されます。\
  なお、既存の組み込みテーマのみが指定可能で、それ以外が指定された場合はビューワの読み込みに失敗します。\
  指定可能な組み込みテーマ次のとおりです：「viewer」「dark」「dark-yellow」「gc-blue」「light」「light-blue」\
  また、このオプションはカスタムテーマを指定するために使用する theme オプションよりも優先されます。
  ```javascript
  // 例：
  var viewer = new GcPdfViewer(selector, { 
	   requireTheme: "light"
  });
  ```
- onThemeChanged イベントを追加しました。ユーザがビューワのテーマを変更したときに発生します。
  ```javascript
  // 例：
  var viewer = new GcPdfViewer(selector, { 
	   requireTheme: localStorage.getItem('demo_theme') || 'viewer'
   });
   viewer.addDefaultPanels();
   viewer.onThemeChanged.register(function(args) {
     localStorage.setItem('demo_theme', args.theme);
   });
   ```
- onInitialized オプションを追加しました。onInitialized ハンドラは、ビューワをインスタンス化した直後に呼び出します。
  ```javascript
  // 例：
  var viewer = new GcPdfViewer("#root", { 
    onInitialized: (viewer)=> { 
      // ビューワの初期化コードをここに記述します。
    } 
  });
  ```
- fieldsAppearance オプションを追加しました。フォームフィールドのレンダリング方法を指定します。\
  このオプションは、フォームフィールドのレンダリングをカスタマイズするために使用します。
  使用可能なレンダリングの外観タイプは以下のとおりです。
  * Custom：デフォルト値です。［Web］の外観ではサポートされていない背景色と枠線のスタイルを追加した外観を使用します。
  * Web：Web のフォームフィールドの標準的な外観を使用します。なお、OS／ブラウザに依存したスタイルで表示されます。
    詳しくは https://developer.mozilla.org/en-US/docs/Web/CSS/appearance をご覧ください。
  * Predefined：PDF から定義済みの外観ストリームを取得して使用します。外観ストリームが利用できない場合は［Custom］の外観を使用します。

  ```javascript  
  // 例1：ラジオボタンとチェックボックスの外観に［Web］を設定します。
  var viewer = new GcPdfViewer("#root", { fieldsAppearance: { radioButton: "Web", checkBoxButton: "Web" } });
  ```
  ```javascript  
  // 例2：ラジオボタンの外観に［Predefined］を設定します。
  var viewer = new GcPdfViewer("#root", { fieldsAppearance: { radioButton: "Predefined" } });
  ```
- GET メソッドを使用してフォームを送信できるようになりました。
  ```csharp
  // GcPdf にて、GET メソッドを使用してフォームを送信する ActionSubmitForm を作成します。
  var actionSubmit = new ActionSubmitForm("/");
  actionSubmit.SubmitFormat = ActionSubmitForm.SubmitFormatType.HtmlForm;
  actionSubmit.HtmlFormFormat = ActionSubmitForm.HtmlFormFormatFlags.GetMethod;
  var btnSubmit = new PushButtonField();
  btnSubmit.Widget.Rect = new RectangleF(50, 100, 100, 50);
  btnSubmit.Widget.ButtonAppearance.Caption = "送信";
  btnSubmit.Widget.Page = page;
  btnSubmit.Widget.Events.Activate = actionSubmit;
  ```
- リセットアクションに FieldNames／ExcludeSpecifiedFields／Next プロパティのサポートを追加しました。
- enableXfa オプションを追加しました。XFA（XML Forms Architecture）フォームがある場合、レンダリングするかどうかを指定します。デフォルトは true です。
  ```javascript
  // 例：XFA フォームのレンダリングを無効にします。
  var viewer = new GcPdfViewer(selector, { enableXfa: false });
  ```
- XFA フォームのテキストコンテンツを選択／コピーする機能を追加しました。
- maxCanvasPixels オプションを追加しました。対応する最大の canvas サイズをピクセル単位（幅×高さ）で指定します。未指定または-1を指定した場合は無制限となります。
  canvas のスケーリングが maxCanvasPixels を超える場合、canvas にページを再描画する代わりに、CSS のスケーリングが使用されます。
- エディタにて最後に使用した値を記憶できるようになりました。\
  editorDefaults オプションに新しく次の設定を追加しました：rememberLastValues／lastValueKeys\
  rememberLastValues に true または undefined を設定した場合、最後に使用したプロパティ値を新しい注釈のデフォルト値として使用します。
  lastValueKeys は、どのプロパティを記憶するかを指定します。
```javascript  
  // lastValueKeys のデフォルト値：
  ["appearanceColor", "borderStyle", "color", "interiorColor", "backgroundColor", "borderColor", "opacity", "textAlignment", "printableFlag", "open",
  "lineStart", "lineEnd", "markBorderColor", "markFillColor", "overlayFillColor", "overlayText", "overlayTextJustification", "newWindow", "calloutLineEnd", "fontSize",
  "fontName", "name", "readOnly", "required"]
```
```javascript  
  // 例：最後に使用した値を記憶しないようにします。
  var viewer = new GcPdfViewer("#root", { editorDefaults: { rememberLastValues: false } });
```
```javascript
  // 例：borderStyle プロパティだけ記憶します。
  var viewer = new GcPdfViewer("#root", { editorDefaults: { rememberLastValues: true, lastValueKeys: ["borderStyle"] } });
```
- [Android] ピンチ操作によるズームに対応しました。


### 変更
- PDF.js ライブラリを v2.0.943 から v2.10.377 に更新しました。更新内容は [PDF.js のリリースノート](https://github.com/mozilla/pdf.js/releases)をご参照ください。
- goToPageNumber メソッドは非推奨になりました。代わりに goToPage メソッドまたは pageIndex プロパティを使用してください。
- ラジオボタンとチェックボックスは PDF にて定義された外観をデフォルトで使用しなくなりました。
  以下のとおり fieldsAppearance オプションを使用すると、以前の動作に戻すことができます。
```javascript  
  var viewer = new GcPdfViewer("#root", { fieldsAppearance: { radioButton: "Predefined", checkBoxButton: "Predefined" } });
```
- SupportApi の情報を取得する ping メソッドは廃止となり使用できなくなりました。代わりに serverVersion メソッドを使用してください。
- SubmitForm／ResetForm プロパティは submitForm／resetForm に名称が変わりました。

### 不具合の修正
- [[4409748657935](https://developer-tools.zendesk.com/hc/ja/articles/4409748657935)] [PDFビューワ] iOS／iPadOS／MacOSのSafariにて拡大・縮小を繰り返すと、PDFのページが表示されなくなる


## [2.2.16] - 2021年9月29日
### 追加
- 定義済みスタンプが使用できるようになりました。\
  新しい stamp オプションを使用して設定を行うことができます。
  ```javascript
  // 例１：「承認」と「否認」のキャプションが付いたカスタムスタンプ２セットをスタンプ注釈のドロップダウンに追加します。
  var viewer = new GcPdfViewer("#root", { 
    stamp: {
      stampCategories: [
        { name: '承認', stampImageUrls: ['http://example.com/stamps/ok.png', 
           'http://example.com/stamps/agree.png', 'http://example.com/stamps/fine.png'] },
        { name: '否認', stampImageUrls: ['http://example.com/stamps/stamps/notok.png', 
          'http://example.com/stamps/disagree.png', 'http://example.com/stamps/noway.png'] },
      ]
    }
  });
  ```
  ```javascript
  // 例２：定義済みスタンプのドロップダウンを非表示にします。
  var viewer = new GcPdfViewer("#root", {     
    stamp: {
      stampCategories: false
    }
  });
  ```
  ```javascript
  // 例３：カスタムスタンプの画像解像度を指定します（指定がない場合は 72 dpi が使用されます）。
  var viewer = new GcPdfViewer("#root", { 
    stamp: {
        dpi: 144,
      stampCategories: [
        { name: 'スタンプ', stampImageUrls: ['stamp1.png', 'stamp2.png', 'stamp3.png'] }
      ]
    }
  });
  ```
- disableFeatures オプションを追加しました。\
  このオプションにて、特定の機能を無効にすることができます（例：セキュリティを考慮して）。\
  無効にできる機能：\
  'JavaScript' | 'AllAttachments' | 'FileAttachments' | 'SoundAttachments' | 'DragAndDrop' | 'SubmitForm' | 'Print'
  ```javascript
  // 例：ドラッグアンドドロップ操作、JavaScript アクション、すべての添付ファイルを無効にします。
  var viewer = new GcPdfViewer("#root", { disableFeatures: ['DragAndDrop', 'JavaScript', 'AllAttachments'] } );
  ```
- [エディタ] 注釈の不透明度に対応しました。
- [エディタ] テキストフィールドとフリーテキスト注釈にフォントファミリーのサポートを追加しました。
- [エディタ] 署名ツールにより作成されたスタンプ注釈を自動的にコンテンツに変換する機能を追加しました。
  ```javascript
  // 使用例：
  var viewer = new GcPdfViewer("#root", signTool: { convertToContent: true });  
  ```
- [エディタ] setPageSize メソッドを追加しました。\
  これにより、newPage メソッドにて独自のページサイズを指定できるようになりました。
  
  ```javascript
  // 例：1ページ目の新しいページサイズを設定します。
  viewer.setPageSize(0, { width: 300, height: 500 } );
  ```
- [フォームエディタ] ページ内のタブの順序を変更する機能を追加しました。
- 注釈の行と列のタブの順序のサポートを追加しました。
- マス目テキストフィールドに必須のバリデーションを実装しました。
- ドキュメント一覧パネルにて現在のドキュメントがハイライト表示されるようになりました。

### 変更
- [エディタ] 選択した注釈／フォームにカーソルを合わせた際のカーソルのスタイルを改善しました。
- [エディタ] いずれかのエディタツールが使用される際、フローティングバーが非表示になるよう変更しました。
- 使用される SupportApi のバージョンが古い場合、警告メッセージが表示されるようになりました。

### 不具合の修正
- [[4404354875535](https://developer-tools.zendesk.com/hc/ja/articles/4404354875535)] [PDFビューワ] PushButtonFieldに設定されたActionURIが正しく動作しない
- [[4404354881295](https://developer-tools.zendesk.com/hc/ja/articles/4404354881295)] [PDFビューワ] 日本語のテキストを含むJavaScriptアクションが正しく動作しない

## [2.1.17] - 2021年5月26日
### 追加
- [iOS] iOS デバイスでファイル選択ダイアログを開くための UI を追加しました。
- [ビューワ] PDF ファイルをドラッグ & ドロップすることで開けるようになりました。
- [エディタ] グラフィカルな署名ツールを追加しました。
- [エディタ] スタンプ注釈に対応しました。（画像をスタンプ注釈として追加できます。また、画像のコンテンツへの変換も可能です。）
- [エディタ] locked プロパティを使用して、編集の際に注釈やフォームフィールドをロックする機能を追加しました。
  ```javascript
  // 使用例:
  var viewer = new GcPdfViewer('#root', { supportApi: { apiUrl: 'api/pdf-viewer', webSocketUrl: false } });
  viewer.addDefaultPanels();
  viewer.addAnnotationEditorPanel();
  viewer.addFormEditorPanel();
  viewer.addReplyTool();
  viewer.onAfterOpen.register(()=>{
    // ドキュメントを開いた後、すべてのテキスト注釈をロックします。
    const resultArr = await viewer.findAnnotation(1, // 1 - AnnotationTypeCode.TEXT
      { findField: 'annotationType',
        pageNumberConstraint: 1, findAll: true });
      viewer.updateAnnotations(0, resultArr.map((data)=> { data.annotation.locked = true; return data.annotation; }));
  });
  // Annotations.pdf を開きます。
  viewer.open('Annotations.pdf');
  ```
- [エディタ] コンテキストメニューを使用して、オブジェクトを次のページまたは前のページに移動できるようになりました。
- [iOS] [Android] スマートフォンやタブレットでの PDF フォームの入力に対応しました。
- [エディタ] リンク注釈に対応しました。
  * 選択したテキストのコンテキストメニューを使用して、リンク注釈を作成できるようになりました。 
  * コンテキストメニューを使用して、注釈やフォームフィールドの上にリンク注釈を作成できるようになりました。
~~~~
  「移動先の表示方法」の説明:
    FitV = FitBV    // ページ高に合わせる
    FitH = FitBH    // ページ幅に合わせる
    Fit = FitB      // ページに合わせる
    FitR            // 設定した矩形に合わせて移動しズーム
    XYZ             // 設定した座標に移動し倍率を適用
~~~~
- [エディタ] Shift キーを使用して、アスペクト比を維持しながら注釈やフォームフィールドのサイズを変更できるようになりました。
- PDF ビューワのメソッドが PDF の JS アクションにて使用できるようになりました。
```javascript
  // 署名ダイアログを表示する JS アクションの例です。
  app.showSignTool();
```
- 新しいメソッドを追加しました。
```javascript
  lockAnnotation    // 編集の際に注釈をロックします。
  unlockAnnotation  // 編集の際に注釈のロックを解除します。
  getPageSize       // ページサイズを返します。
                    // デフォルトではスケーリングされていないサイズを返しますが、
                    // スケーリングされた値を取得したい場合は、
                    // includeScale 引数に true を渡します。
  getPageRotation   // 閲覧時のページの回転値を取得します。
```
- 新しいオプション coordinatesPrecision を追加しました。
```javascript
  // 注釈やフォームフィールドの配置座標の精度を設定できます。
  // 注釈エディタとフォームエディタに設定が反映されます。 
  // デフォルトは 1（小数点以下は四捨五入）です。
  // 使用例:
  // デフォルトの精度を 0.001（端数は四捨五入）に変更します。
  var viewer = new GcPdfViewer("#root", { coordinatesPrecision: 0.001 } );
```
- [エディタ] サイズ変更 / 移動ハンドルのデフォルトサイズを変更できるようになりました。
  editorDefaults オプションを使用して resizeHandleSize と moveHandleSize の設定を調整します。
  デフォルト値は resizeHandleSize が 8 ピクセルで、moveHandleSize が 14 ピクセルです。
```javascript
  // サンプルコード:
  var viewer = new GcPdfViewer("#root", {
       editorDefaults: {
       resizeHandleSize: 20,
       moveHandleSize: 40,
       dotHandleSize = 20
   },
   supportApi: { apiUrl: 'support-api/gc-pdf-viewer', webSocketUrl: false }
 });
```
- 「手のひらツール」と「テキスト選択ツール」を含むフローティングバーを追加しました。
  フローティングバーは、編集モードではデフォルトで表示されます。
  editorDefaults.hideFloatingBar の設定により、フローティングバーを非表示にできます。
```javascript
  // サンプルコード:
  var viewer = new GcPdfViewer("#root", {
     editorDefaults: {
         hideFloatingBar: true
     },
     supportApi: { apiUrl: 'support-api/gc-pdf-viewer', webSocketUrl: false }
   });
```
- SupportApi が利用可能な場合、「GrapeCity PDF ビューワ について」のダイアログに SupportApi のバージョンが表示されるようになりました。
- 同一グループ内のラジオボタンの出力値を自動生成する機能を実装しました。
- プッシュボタンにて、MouseUp / MouseDown イベントのアクションに対応しました。
- validateForm メソッドを使用して、カスタムバリデーションを実行できるようになりました。
```javascript
  // 使用例:
  // すべてのフォームフィールドを検証します。
  // 各フィールドの値は「YES」または「NO」である必要があります。
  viewer.validateForm((fieldValue, field) => { return (fieldValue === "YES" || fieldValue === "NO") ? true : "入力可能な値は YES または NO です。"; });
```
- 新しい API メソッド setAnnotationBounds が追加されました。注釈の位置やサイズをプログラムにて設定できます。
 ```javascript
 // 例: 注釈を左上に移動します。
 viewer.setAnnotationBounds('1R', {x: 0, y: 0});
 // 例: 注釈を左下に移動します。
 viewer.setAnnotationBounds('1R', {x: 0, y: 0}, 'BottomLeft');
 // 例: 注釈のサイズを 40 x 40 ポイントに設定します。
 viewer.setAnnotationBounds('1R', {w: 40, h: 40});
 // 例: 注釈の位置を x = 50、y = 50（原点は左上）、サイズを 40 x 40 ポイントに設定します。
 viewer.setAnnotationBounds('1R', {x: 50, y: 50, w: 40, h: 40});
 ```
- 行と列の注釈タブの順序に対応しました。
- マス目フィールドに必須入力のバリデーションを設定できるようになりました。 

### 変更
- [Android/Chrome] 下にスワイプするとページが更新されるようになりました。
- [iOS] iOSデバイスのデフォルトのズームモードを「ページ幅に合わせる」に変更しました。
- 検証に失敗または空の必須フィールドがあるフォームは送信できなくなりました。代わりに、最初に失敗したフィールドがフォーカスされ、エラーを示すツールチップとともに強調表示されます。
- パスワード入力ダイアログのスタイルを改善しました。
- repaint メソッドにオプションの indicesToRepaint 引数を指定できるようになりました。
```javascript
// 使用例:
// インデックスが 0 と 3 のページのコンテンツと注釈を再描画します。
viewer.repaint([0, 3]);
```

## [2.0.17] - 2021年2月17日
### 追加
- 日本語版としての初版となります。
