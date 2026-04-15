# サーバー監視ダッシュボード

Laravel 11 と Vite を活用し、サーバーの負荷状況をリアルタイムで可視化するアプリです。

## 🛠 実装のポイント
- **リアルタイム・モニタリング**: 
  1秒ごとにサーバーのCPU、メモリ、ディスク使用率をAPI経由で取得し、グラフを更新します。
- **技術的課題の解決**: 
  GitHub Codespaces 特有の HMR（Hot Module Replacement）接続エラーや WebSocket の通信制限、CORSポリシーの問題を、Vite のカスタム設定とプロキシ最適化により自力で解決しました。
- **セキュアなデータ取得**: 
  Laravel Breeze による認証をベースに、認証済みユーザーのみが統計データにアクセスできるセキュアなエンドポイントを構築しました。

## 🚀 使用技術
- **Backend**: Laravel 11 / PHP 8.2
- **Frontend**: Vite / Vanilla JS / SVG
- **Infrastructure**: GitHub Codespaces
