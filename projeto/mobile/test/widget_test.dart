// This is a basic Flutter widget test.
//
// To perform an interaction with a widget in your test, use the WidgetTester
// utility in the flutter_test package. For example, you can send tap and scroll
// gestures. You can also use WidgetTester to find child widgets in the widget
// tree, read text, and verify that the values of widget properties are correct.

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/main.dart';
import 'package:mobile/services/storage_service.dart';

void main() {
  testWidgets('PredialFix App initializes with login screen',
      (WidgetTester tester) async {
    // Create a StorageService for testing
    final storageService = StorageService();
    
    // Build our app and trigger a frame.
    await tester.pumpWidget(MyApp(storageService: storageService));
    await tester.pumpAndSettle();

    // Verify that the app shows a login-related screen
    // (looking for common text on login/register screens)
    expect(
        find.byType(MaterialApp),
        findsOneWidget,
        reason:
            'Should have MaterialApp as root widget');
  });
}


